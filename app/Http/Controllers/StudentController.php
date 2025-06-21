<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    // Load student
    public function index(Request $request) {
        $user = Auth::user();

        if($request->ajax()) {
            $students = Student::with(['user' => function($query) {
                                    $query->select('id', 'name');
                                }])
                                ->when($user->role == 'teacher', function($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                })->get();

            return DataTables::of($students)
                    ->addIndexColumn()
                    ->addColumn('name', function($row) {
                        return !empty($row->name) ? $row->name : '';
                    })
                    ->addColumn('email', function($row) {
                        return !empty($row->email) ? $row->email : '';
                    })
                    ->addColumn('phone', function($row) {
                        return !empty($row->phone) ? $row->phone : '';
                    })
                    ->addColumn('address', function($row) {
                        return !empty($row->address) ? $row->address : '';
                    })
                    ->addColumn('class', function($row) {
                        return !empty($row->class) ? $row->class : '';
                    })
                    ->addColumn('roll_number', function($row) {
                        return !empty($row->roll_number) ? $row->roll_number : '';
                    })
                    ->addColumn('user_id', function($row) {
                        return !empty($row->user->name) ? $row->user->name : '';
                    })
                    ->addColumn('created_at', function($row) {
                        return !empty($row->created_at) ? $row->created_at->format('Y-m-d H:i') : '';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '';
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-primary edit-btn mx-2">EDIT</button>';
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-danger delete-btn">DELETE</button>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $userRole = ($user->role == 'teacher') ? true : false;
        return view('students.index',compact('userRole'));
    }

    // store teacher
    public function store(StudentRequest $request) {
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "address" => $request->address,
            "class" => $request->class,
            "user_id" => Auth::user()->id
        ];

        try{
            Student::create($data);
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Student created successfully.'),200);
    }
    
    // edit teacher
    public function edit(Request $request) {
        $id = ($request->id) ? $request->id : '';

        try{
            $student = Student::find($id);
            $formHtml = view('students.form',compact('student'))->render();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        $data = [
            'html' => $formHtml
        ];

        return response()->json($this->ajaxResponse(true,$data,''),200);
    }

    // store teacher
    public function update(StudentRequest $request) {
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "address" => $request->address,
            "class" => $request->class,
            "user_id" => Auth::user()->id
        ];

        try{
            Student::where('id',$request->student_id)->update($data);
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Student updated successfully.'),200);
    }

    // delete teacher
    public function delete(Request $request) {
        $id = !empty($request->id) ? $request->id : '';
        try{
            Student::where('id',$id)->delete();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Student deleted successfully.'),200);
    }

    public function ajaxResponse($status,$data,$message) {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];
    }
}
