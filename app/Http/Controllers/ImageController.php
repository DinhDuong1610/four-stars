<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\CreateImage;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index() {
        $images = Image::all();

        return response()->json([
            'data' => $images
        ], 200);
    }

    public function store(CreateImage $request) {
        $image = new Image();
        $image->folder_id = $request->folder_id;
        $image->path = $request->path;
        $image->save();
        
        return response()->json([
            'message' => 'Image created successfully',
            'data' => $image
        ], 201);
    }
}
