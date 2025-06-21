<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::get('/',[LoginController::class,'viewLogin'])->name('login');
Route::post('/login',[LoginController::class,'login'])->name('user.login');


Route::group(['middleware' => ['auth']], function() {
    Route::post('/logout',[LoginController::class,'logout'])->name('user.logout');
    Route::get('/dashboard',[AnnouncementController::class,'dashboard'])->name('user.announcement');
    Route::post('/store',[AnnouncementController::class,'store'])->name('announcement.store');
    Route::get('/view',[AnnouncementController::class,'view'])->name('announcement.view');
    Route::get('/students',[StudentController::class,'index'])->name('student.index');
    Route::get('/parents',[ParentsController::class,'index'])->name('parents.index');

    Route::group(['middleware' => ['auth','role:admin']], function() {
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/teacher',[TeacherController::class,'index'])->name('admin.teacher');
            Route::post('/store',[TeacherController::class,'store'])->name('teacher.store');
            Route::get('/edit',[TeacherController::class,'edit'])->name('teacher.edit');
            Route::post('/delete',[TeacherController::class,'delete'])->name('teacher.delete');
            Route::post('/update',[TeacherController::class,'update'])->name('teacher.update');
        });
    });

    Route::group(['middleware' => ['auth','role:teacher']], function() {
        Route::group(['prefix' => 'teacher'], function () {
            // students routes
            Route::group(['prefix' => 'student'], function () {
                Route::post('/store',[StudentController::class,'store'])->name('student.store');
                Route::get('/edit',[StudentController::class,'edit'])->name('student.edit');
                Route::post('/update',[StudentController::class,'update'])->name('student.update');
                Route::delete('/delete',[StudentController::class,'delete'])->name('student.delete');
            });

            // Manage parents
            Route::group(['prefix' => 'parent'], function () {
                Route::post('/store',[ParentsController::class,'store'])->name('parent.store');
                Route::get('/edit',[ParentsController::class,'edit'])->name('parent.edit');
                Route::post('/update',[ParentsController::class,'update'])->name('parent.update');
                Route::delete('/delete',[ParentsController::class,'delete'])->name('parent.delete');
            });
        });
    });
});