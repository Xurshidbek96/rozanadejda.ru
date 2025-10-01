<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    private const SLIDES_DIRECTORY = 'images/slides/';

    /**
     * Display a listing of slides
     */
    public function index()
    {
        $slides = Slide::all();
        return $this->apiResponse($slides);
    }

    /**
     * Store a newly created slide
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $slideData = [];
        
        if ($request->hasFile('image')) {
            $slideData['image'] = $this->uploadFile('image', self::SLIDES_DIRECTORY);
        }

        $slide = Slide::create($slideData);
        return $this->apiResponse($slide);
    }

    /**
     * Display the specified slide
     */
    public function show(Slide $slide)
    {
        return $this->apiResponse($slide);
    }

    /**
     * Update the specified slide
     */
    public function update(Request $request, Slide $slide)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $slideData = [];

        if ($request->hasFile('image')) {
            // Delete old image
            $this->deleteFile(self::SLIDES_DIRECTORY, $slide->image);
            
            // Upload new image
            $slideData['image'] = $this->uploadFile('image', self::SLIDES_DIRECTORY);
        }

        $slide->update($slideData);
        return $this->apiResponse($slide);
    }

    /**
     * Remove the specified slide
     */
    public function destroy(Slide $slide)
    {
        $this->deleteFile(self::SLIDES_DIRECTORY, $slide->image);
        $slide->delete();
        
        return $this->apiResponse(['message' => 'Slide deleted successfully']);
    }
}
