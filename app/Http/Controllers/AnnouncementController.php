<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Jobs\SendEmailNotification;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    // User announcement listss
    public function dashboard(Request $request) {
        if($request->ajax()) {
            $announcements = Announcement::with(['users' => function($query) {
                                            $query->select('id', 'name');
                                        }])->get();

            return DataTables::of($announcements)
                    ->addIndexColumn()
                    ->addColumn('title', function($row) {
                        return !empty($row->title) ? $row->title : '';
                    })
                    ->addColumn('content', function($row) {
                        return !empty($row->content) ? $row->content : '';
                    })
                    ->addColumn('created_by_id', function($row) {
                        return !empty($row->users->name) ? $row->users->name : '';
                    })
                    ->addColumn('target', function($row) {
                        return (!empty($row->target) && $row->target == 'all') ? ucfirst('Student And Parents') : (!empty($row->target) ? ucfirst($row->target) : '');
                    })
                    ->addColumn('created_at', function($row) {
                        return !empty($row->created_at) ? $row->created_at->format('Y-m-d H:i') : '';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<button type="button" data-id="'.$row->id.'" class="btn btn-danger view-btn">View</button>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        $authUserRole = (Auth::user()->role == 'teacher') ? true : false;

        return view('announcements.index',compact('authUserRole'));
    }

    // User announcement Store
    public function store(AnnouncementRequest $request) {
        $user = Auth::user();
        $target =  (!empty($user) && $user->role == 'admin') ? 'teachers' : (!empty($request->target) ? $request->target : '');
        $is_send_email = (!empty($user) && $user->role == 'teacher') ? 1 : 0;

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'target' => $target,
            'created_by_id' => $user->id,
            'created_by_type' => (!empty($user) && $user->role == 'teacher') ? 'Teacher' : 'Admin',
            'email_notification' => $is_send_email,
        ];

        try{
            $annoucement = Announcement::create($data);
            if($is_send_email) {
                SendEmailNotification::dispatch($user->id,$target,$annoucement);
            }
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,[],'Announcement created successfully.'),200);
    }

    // View content
    public function view(Request $request){
        $authUserRole = (Auth::user()->role == 'teacher') ? true : false;
        $announcement_id = !empty($request->id) ? $request->id : '';
        try {
            $announcement = Announcement::select('title','content')->find($announcement_id);
            $formHtml = view('announcements.form',compact('announcement','authUserRole'))->render();
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,['formHtml' => $formHtml],''),200);
    }

    public function ajaxResponse($status,$data,$message) {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];
    }
}
