<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ProductUpdateRequest extends FormRequest
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
            return;
        }

        $uploaded = $this->file('files');
        if ($uploaded instanceof UploadedFile) {
            $this->files->set('files', [$uploaded]);
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
