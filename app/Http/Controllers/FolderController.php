<?php

namespace App\Http\Controllers;

use App\Http\Requests\Folder\CreateFolder;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::all();

        return response()->json([
            'data' => $folders
        ], 200);
    }

    public function store(CreateFolder $request)
    {
        $folder = new Folder();
        $folder->name = $request->name;
        $folder->parent_id = $request->parent_id;
        $folder->save();

        return response()->json([
            'message' => 'Folder created successfully',
            'data' => $folder
        ], 201);
    }

    // public function show(Request $request) {
    //     $faculty = $request->query('faculty');
    //     $year = $request->query('year');
    //     $class = $request->query('clas');
    //     $course = $request->query('course');
    //     $section = $request->query('section');

    //     $faculty_id = Folder::where('name', $faculty)->firstOr(null);
    //     $year_id = null;
    //     $class_id = null;
    //     $course_id = null;
    //     $section_id = null;

    //     $folders = null;

    //     if(!$year) {
    //         $folders = Folder::where('parent_id', $faculty_id)->get();
    //     } else if(!$class) {
    //         $year_id = Folder::where('parent_id', $faculty_id)->where('name', $year)->firstOr(null);
    //         $folders = Folder::where('parent_id', $year_id)->get();
    //     } else if(!$course) {
    //         $year_id = Folder::where('parent_id', $faculty_id)->where('name', $year)->firstOr(null);
    //         $class_id = Folder::where('parent_id', $year_id)->where('name', $class)->firstOr(null);

    //         $folders = Folder::where('parent_id', $class_id)->get();
    //     } else if(!$section){
    //         $year_id = Folder::where('parent_id', $faculty_id)->where('name', $year)->firstOr(null);
    //         $class_id = Folder::where('parent_id', $year_id)->where('name', $class)->firstOr(null);
    //         $course_id = Folder::where('parent_id', $class_id)->where('name', $course)->firstOr(null);

    //         $folders = Folder::where('parent_id', $course_id)->get();
    //     } else {
    //         $year_id = Folder::where('parent_id', $faculty_id)->where('name', $year)->firstOr(null);
    //         $class_id = Folder::where('parent_id', $year_id)->where('name', $class)->firstOr(null);
    //         $course_id = Folder::where('parent_id', $class_id)->where('name', $course)->firstOr(null);
    //         $section_id = Folder::where('parent_id', $course_id)->where('name', $section)->firstOr(null);

    //         $folders = Folder::where('parent_id', $section_id)->get();
    //     }

    //     return response()->json([
    //         'message' => 'List of folders',
    //         'data' => $folders
    //     ], 201); 
    // }


    public function show(Request $request)
    {
        // Lấy các tham số từ query string
        $faculty = $request->query('faculty');
        $year = $request->query('year');
        $class = $request->query('class');
        $course = $request->query('course');
        $section = $request->query('section');

        // Tìm thư mục tương ứng với "faculty"
        $faculty_id = Folder::where('name', $faculty)->whereNull('parent_id')->first();  // Thư mục gốc, không có parent_id

        // Kiểm tra và tìm các thư mục con theo các cấp
        $folders = null;

        // Nếu không có "year", lấy danh sách các thư mục con của "faculty"
        if (!$year) {
            $folders = Folder::where('parent_id', $faculty_id->id)->get();
        } else {
            // Nếu có "year", tìm thư mục "year" trong "faculty"
            $year_id = Folder::where('parent_id', $faculty_id->id)->where('name', $year)->first();

            if (!$year_id) {
                return response()->json(['message' => 'Year not found'], 404); // Nếu không tìm thấy năm
            }

            // Nếu không có "class", lấy danh sách các thư mục con của "year"
            if (!$class) {
                $folders = Folder::where('parent_id', $year_id->id)->get();
            } else {
                // Nếu có "class", tìm thư mục "class" trong "year"
                $class_id = Folder::where('parent_id', $year_id->id)->where('name', $class)->first();

                if (!$class_id) {
                    return response()->json(['message' => 'Class not found'], 404); // Nếu không tìm thấy lớp
                }

                // Nếu không có "course", lấy danh sách các thư mục con của "class"
                if (!$course) {
                    $folders = Folder::where('parent_id', $class_id->id)->get();
                } else {
                    // Nếu có "course", tìm thư mục "course" trong "class"
                    $course_id = Folder::where('parent_id', $class_id->id)->where('name', $course)->first();

                    if (!$course_id) {
                        return response()->json(['message' => 'Course not found'], 404); // Nếu không tìm thấy khóa học
                    }

                    // Nếu có "section", tìm thư mục "section" trong "course"
                    if ($section) {
                        $section_id = Folder::where('parent_id', $course_id->id)->where('name', $section)->first();

                        if (!$section_id) {
                            return response()->json(['message' => 'Section not found'], 404); // Nếu không tìm thấy phần
                        }

                        // Trả về thông tin của thư mục "section" nếu có
                        $folders = Folder::where('parent_id', $section_id->id)->get();
                    } else {
                        // Trả về tất cả thư mục con của "course"
                        $folders = Folder::where('parent_id', $course_id->id)->get();
                    }
                }
            }
        }

        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'data' => $folders
        ], 200);
    }
}
