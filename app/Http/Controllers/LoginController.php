<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function viewLogin() {
        if(Auth::check()) {
            return redirect()->route('user.announcement');
        }
        return view('login');
    }

    public function login(LoginRequest $request) {
        $credintials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(!Auth::validate($credintials)) {
            return response()->json($this->ajaxResponse(false,[],'Please enter valid email and password.'),200);    
        }

        try{
            Auth::attempt($credintials);
            $user = Auth::user();

            $redirectUrl = route('user.announcement');
        } catch (\Exception $e) {
            return response()->json($this->ajaxResponse(false,[],$e->getMessage()),200);
        }

        return response()->json($this->ajaxResponse(true,['redirect' => $redirectUrl],'loged in succeessfully.'),200);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

    public function ajaxResponse($status,$data,$message) {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];
    }
}
