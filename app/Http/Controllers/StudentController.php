<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request) {
        $folderId = $request->query('folder_id');

        $students = Student::where('folder_id', $folderId)->get();

        return response()->json([
            'data' => $students
        ], 200);
    }

    public function store(Request $request) {

    }

    public function update(Request $request, $id) {

    }

    public function updateMultiple(Request $request) {
        $studentsData = $request->students; 
    
        foreach ($studentsData as $studentData) {
            $student = Student::where('msv', $studentData['msv'])->first(); 

            $student->score = $studentData['score'];
            $student->save();
        }
    
        return response()->json(['message' => 'Students updated successfully.']);
    }
}
