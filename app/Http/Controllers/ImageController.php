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

    // public function store(CreateImage $request) {
    //     if ($request->image) {
    //         $images = [];
    
    //         // Duyệt qua tất cả các file ảnh và lưu vào storage
    //         foreach ($request->image as $file) {
    //             // Lưu ảnh vào thư mục 'images' trong 'storage/app/public' và lấy tên ảnh
    //             $imagePath = $file->store('images', 'public');
    
    //             // Tạo một bản ghi mới cho ảnh
    //             $image = new Image();
    //             $image->folder_id = $request->folder_id;  // Lưu folder_id từ request
    //             $image->path = $imagePath;  // Lưu đường dẫn ảnh vào cơ sở dữ liệu
    //             $image->save();
    
    //             // Lưu vào mảng images để trả về sau này nếu cần
    //             $images[] = $image;
    //         }
    
    //         return response()->json([
    //             'message' => 'Images uploaded successfully',
    //             'data' => $images
    //         ], 201);
    //     }
    
    //     return response()->json([
    //         'message' => 'No images provided'
    //     ], 400);
    // }

    public function store(CreateImage $request) {
        // Kiểm tra xem có file ảnh nào được gửi đến không
        if ($request->hasFile('image')) {
            // Lưu ảnh vào thư mục 'images' trong 'storage/app/public' và lấy tên ảnh
            $imagePath = $request->file('image')->store('images', 'public');
    
            // Tạo một bản ghi mới cho ảnh
            $image = new Image();
            $image->folder_id = $request->folder_id;  // Lưu folder_id từ request
            $image->name = $request->name;  // Lấy tên ảnh từ request
            $image->size = $request->size;
            $image->path = $imagePath;  // Lưu đường dẫn ảnh vào cơ sở dữ liệu
            $image->save();
    
            // Trả về phản hồi với thông báo thành công
            return response()->json([
                'message' => 'Image uploaded successfully',
                'data' => $image, // Trả lại thông tin ảnh đã lưu
            ], 201);
        }
    
        return response()->json([
            'message' => 'No image provided'
        ], 400);
    }
    
}
