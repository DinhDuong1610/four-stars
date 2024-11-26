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
        $excel = new Excel();
        $excel->folder_id = $request->folder_id;
        $excel->path = $request->path;
        $excel->save();
        
        return response()->json([
            'message' => 'Excel created successfully',
            'data' => $excel
        ], 201);
    }
}
