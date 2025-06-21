<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
    // load teachers
    public function index(Request $request) {
        if($request->ajax()) {
            $teachers = User::where('role', 'teacher')->get();

            return DataTables::of($teachers)
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
                    ->addColumn('subject', function($row) {
                        return !empty($row->subject) ? $row->subject : '';
                    })
                    ->addColumn('bio', function($row) {
                        return !empty($row->bio) ? $row->bio : '';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '';
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-primary edit-btn mx-2">EDIT</button>';
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-danger delete-btn">DELETE</button>';
                        return $btn;
                    })
                    ->rawColumns(['action','image','status'])
                    ->make(true);
        }
        return view('teachers.index');
    }

    // store teacher
    public function store(TeacherRequest $request) {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'subject' => $request->subject,
            'bio' => $request->bio,
            'role' => 'teacher'
        ];

        try{
            User::create($data);
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Teacher created successfully.'),200);
    }

    // edit teacher
    public function edit(Request $request) {
        $teacher_id = ($request->id) ? $request->id : '';

        try{
            $teacher = User::find($teacher_id);
            $formHtml = view('teachers.form',compact('teacher'))->render();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        $data = [
            'html' => $formHtml
        ];

        return response()->json($this->ajaxResponse(true,$data,''),200);
    }

    // delete teacher
    public function delete(Request $request) {
        $id = !empty($request->id) ? $request->id : '';
        try{
            User::where('id',$id)->delete();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Teacher deleted successfully.'),200);
    }

    // update teacher
    public function update(TeacherUpdateRequest $request) {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'bio' => $request->bio,
            'role' => 'teacher'
        ];

        if(!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        try{
            User::where('id',$request->teacher_id)->update($data);
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Teacher created successfully.'),200);
    }

    public function ajaxResponse($status,$data,$message) {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];
    }
}
