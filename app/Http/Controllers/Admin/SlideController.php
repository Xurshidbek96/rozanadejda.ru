<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = Slide::all();
        return $this->checkData($slides);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->only('image');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('images/slides'), $filename);
            $requestData['image'] = $filename;
        }
        $slide = Slide::create($requestData);
        return $this->checkData($slide);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slide $slide)
    {
        return $this->checkData($slide);
    }

    /**
     * Update the specified resource in storage.
     */
    public function slideUpdate(Request $request, Slide $slide)
    {
        $requestData = $request->only('image');
        if ($request->hasFile('image')) {
            $this->unlink_file('images/slides/', $slide->image);
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('images/slides'), $filename);
            $requestData['image'] = $filename;
        }
        $slide->update($requestData);
        return $this->checkData($slide);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slide $slide)
    {
        $this->unlink_file('images/slides/', $slide->image);
        $data = $slide->delete();
        return $this->checkData($data);
    }
}
