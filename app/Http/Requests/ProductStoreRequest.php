<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->hasFile('files')) {
            if ($this->request->has('files')) {
                $this->request->remove('files');
            }

            return;
        }

        $uploaded = $this->file('files');
        if ($uploaded instanceof UploadedFile) {
            $this->files->set('files', [$uploaded]);

            return;
        }

        if (is_array($uploaded)) {
            $only = array_values(array_filter(
                $uploaded,
                static fn ($f): bool => $f instanceof UploadedFile && $f->isValid()
            ));
            if ($only === []) {
                $this->files->remove('files');
                $this->request->remove('files');

                return;
            }
            $this->files->set('files', $only);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'numeric',
            'price' => 'numeric',
            'quantity' => 'numeric',
            'files' => ['sometimes', 'array'],
            'files.*' => [
                'file',
                'mimes:jpeg,jpg,png,gif,webp,mp4,webm,mov,avi',
                'max:51200',
            ],
        ];
    }
}
