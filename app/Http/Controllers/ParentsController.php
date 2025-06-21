<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentRequest;
use App\Models\Parents;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ParentsController extends Controller
{
    // parents list
    public function index(Request $request) {
        $user = Auth::user();

        if($request->ajax()) {
            $parents = Parents::when($user->role === 'teacher', function ($query) use ($user) {
                                $query->whereHas('student', function ($q) use ($user) {
                                    $q->where('user_id', $user->id);
                                });
                            })->get();

            return DataTables::of($parents)
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
                    ->addColumn('occupation', function($row) {
                        return !empty($row->occupation) ? $row->occupation : '';
                    })
                    ->addColumn('user_id', function($row) {
                        return !empty($row->student->user->name) ? $row->student->user->name : '';
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
        $students = Student::select('id','name')->where('user_id', $user->id)->get();
        return view('parents.index',compact('userRole','students'));
    }

    // store teacher
    public function store(ParentRequest $request) {
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "occupation" => $request->occupation,
            "student_id" => $request->student_id
        ];

        try{
            Parents::create($data);
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Parent created successfully.'),200);
    }
    
    // edit teacher
    public function edit(Request $request) {
        $id = ($request->id) ? $request->id : '';

        try{
            $students = Student::select('id','name')->where('user_id',Auth::user()->id)->get();
            $parent = Parents::find($id);
            $formHtml = view('parents.form',compact('parent','students'))->render();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        $data = [
            'html' => $formHtml
        ];

        return response()->json($this->ajaxResponse(true,$data,''),200);
    }

    // store teacher
    public function update(ParentRequest $request) {    
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "occupation" => $request->occupation,
            "student_id" => $request->student_id
        ];

        try{
            Parents::where('id',$request->parent_id)->update($data);
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Parent updated successfully.'),200);
    }

    // delete teacher
    public function delete(Request $request) {
        $id = !empty($request->id) ? $request->id : '';
        try{
            Parents::where('id',$id)->delete();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Parent deleted successfully.'),200);
    }

    public function ajaxResponse($status,$data,$message) {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];
    }
}
