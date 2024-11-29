<?php

namespace App\Http\Controllers;

use App\Http\Requests\Excel\CreateExcel;
use App\Models\Excel;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function index() {
        $excels = Excel::all();

        return response()->json([
            'data' => $excels
        ], 200);
    }

    public function store(CreateExcel $request) {
        // Kiểm tra xem có file Excel nào được gửi đến không
        if ($request->hasFile('excel')) {
            // Lưu file Excel vào thư mục 'excels' trong 'storage/app/public' và lấy tên file
            $excelPath = $request->file('excel')->store('excels', 'public');
    
            // Tạo một bản ghi mới cho file Excel
            $excel = new Excel();
            $excel->folder_id = $request->folder_id;  // Lưu folder_id từ request
            $excel->name = $request->name;  // Lưu tên file từ request
            $excel->size = $request->size;
            $excel->path = $excelPath;  // Lưu đường dẫn file vào cơ sở dữ liệu
            $excel->save();
    
            // Trả về phản hồi với thông báo thành công
            return response()->json([
                'message' => 'Excel uploaded successfully',
                'data' => $excel, // Trả lại thông tin file đã lưu
            ], 201);
        }
    
        return response()->json([
            'message' => 'No file provided'
        ], 400);
    }
}
