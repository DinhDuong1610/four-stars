<?php

namespace App\Http\Controllers;

use App\Http\Requests\Folder\CreateFolder;
use App\Models\Excel;
use App\Models\Folder;
use App\Models\Image;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::where('parent_id', null)->get();

        return response()->json([
            'data' => $folders
        ], 200);
    }

    public function store(CreateFolder $request)
    {
        $folder = new Folder();
        $folder->name = $request->name;
        if($request->parent_id) {
            $folder->parent_id = $request->parent_id;
        }
        $folder->save();

        return response()->json([
            'message' => 'Folder created successfully',
            'data' => $folder
        ], 201);
    }

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
        $folders = [];
        $images = [];
        $excels = [];

        // Nếu không có "year", lấy danh sách các thư mục con của "faculty"
        if (!$year) {
            $folders = Folder::where('parent_id', $faculty_id->id)->orderBy('created_at', 'desc')->get();
        } else {
            // Nếu có "year", tìm thư mục "year" trong "faculty"
            $year_id = Folder::where('parent_id', $faculty_id->id)->where('name', $year)->first();

            if (!$year_id) {
                return response()->json(['message' => 'Year not found'], 404); // Nếu không tìm thấy năm
            }

            // Nếu không có "class", lấy danh sách các thư mục con của "year"
            if (!$class) {
                $folders = Folder::where('parent_id', $year_id->id)->orderBy('created_at', 'desc')->get();
            } else {
                // Nếu có "class", tìm thư mục "class" trong "year"
                $class_id = Folder::where('parent_id', $year_id->id)->where('name', $class)->first();

                if (!$class_id) {
                    return response()->json(['message' => 'Class not found'], 404); // Nếu không tìm thấy lớp
                }

                // Nếu không có "course", lấy danh sách các thư mục con của "class"
                if (!$course) {
                    $folders = Folder::where('parent_id', $class_id->id)->orderBy('created_at', 'desc')->get();
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
                        $excels = Excel::where('folder_id', $section_id->id)->orderBy('created_at', 'desc')->get();
                        $images = Image::where('folder_id', $section_id->id)->orderBy('created_at', 'desc')->get();
                    } else {
                        // Trả về tất cả thư mục con của "course"
                        $folders = Folder::where('parent_id', $course_id->id)->orderBy('created_at', 'desc')->get();
                    }
                }
            }
        }

        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'folders' => $folders,
            'excels' => $excels,
            'images' => $images
        ], 200);
    }

    public function getFolderParent(Request $request) {
        $faculty = $request->faculty;
        $year = $request->year;
        $class = $request->class;
        $course = $request->course;
        $section = $request->section;
        

        $folder = new Folder();

        // Tìm thư mục tương ứng với "faculty"
        $faculty_id = Folder::where('name', $faculty)->whereNull('parent_id')->first();  // Thư mục gốc, không có parent_id

        // Nếu không có "year", lấy danh sách các thư mục con của "faculty"
        if (!$year) {
            $folder = null;
        } else {
            // Nếu có "year", tìm thư mục "year" trong "faculty"
            $year_id = Folder::where('parent_id', $faculty_id->id)->where('name', $year)->first();

            if (!$year_id) {
                return response()->json(['message' => 'Year not found'], 404); // Nếu không tìm thấy năm
            }

            // Nếu không có "class", lấy danh sách các thư mục con của "year"
            if (!$class) {
                $folder = $faculty_id;
            } else {
                // Nếu có "class", tìm thư mục "class" trong "year"
                $class_id = Folder::where('parent_id', $year_id->id)->where('name', $class)->first();

                if (!$class_id) {
                    return response()->json(['message' => 'Class not found'], 404); // Nếu không tìm thấy lớp
                }

                // Nếu không có "course", lấy danh sách các thư mục con của "class"
                if (!$course) {
                    $folder = $year_id;
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

                        $folder = $course_id;
                    } else {
                        $folder = $class_id;
                    
                    }
                }
            }
        }
        return response()->json([
            'folder' => $folder
        ], 200);
    }

    public function getFolderCurrent(Request $request) {
        $faculty = $request->faculty;
        $year = $request->year;
        $class = $request->class;
        $course = $request->course;
        $section = $request->section;
        

        $folder = new Folder();

        // Tìm thư mục tương ứng với "faculty"
        $faculty_id = Folder::where('name', $faculty)->whereNull('parent_id')->first();  // Thư mục gốc, không có parent_id

        // Nếu không có "year", lấy danh sách các thư mục con của "faculty"
        if (!$year) {
            $folder = $faculty_id;
        } else {
            // Nếu có "year", tìm thư mục "year" trong "faculty"
            $year_id = Folder::where('parent_id', $faculty_id->id)->where('name', $year)->first();

            if (!$year_id) {
                return response()->json(['message' => 'Year not found'], 404); // Nếu không tìm thấy năm
            }

            // Nếu không có "class", lấy danh sách các thư mục con của "year"
            if (!$class) {
                $folder = $year_id;
            } else {
                // Nếu có "class", tìm thư mục "class" trong "year"
                $class_id = Folder::where('parent_id', $year_id->id)->where('name', $class)->first();

                if (!$class_id) {
                    return response()->json(['message' => 'Class not found'], 404); // Nếu không tìm thấy lớp
                }

                // Nếu không có "course", lấy danh sách các thư mục con của "class"
                if (!$course) {
                    $folder = $class_id;
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

                        $folder = $section_id;
                    } else {
                        $folder = $course_id;
                    
                    }
                }
            }
        }
        return response()->json([
            'folder' => $folder
        ], 200);
    }   
}
