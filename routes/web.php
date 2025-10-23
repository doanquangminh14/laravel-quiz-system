<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ✅ Route chính (Home)
Route::get('/',[UserController::class,'welcome']);
//Route::get('/', [UserController::class, 'welcome'])->name('home');

// ✅ Cấu hình redirect sau khi đăng nhập hoặc đăng ký
Route::get('/dashboard', function () {
    return redirect('/'); // Chuyển về trang chủ
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Cấu hình Profile chuẩn Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Nhúng các route Auth của Breeze (login, register, logout, reset password,...)
require __DIR__.'/auth.php';

// ---------------------- USER & QUIZ ----------------------

Route::get('categories-list', [UserController::class, 'categories']);
Route::get('user-quiz-list/{id}/{category}', [UserController::class, 'userQuizList']);
Route::get('start-quiz/{id}/{name}', [UserController::class, 'startQuiz'])->middleware('auth');;
Route::get('search-quiz', [UserController::class, 'searchQuiz']);
Route::get('leaderboard', [UserController::class, 'leaderboard'])->name('leaderboard');
//Route::get('leaderboard/category/{categoryId}', [UserController::class, 'leaderboard'])->name('leaderboard.category');


Route::middleware('auth')->group(function () {
    Route::get('certificate', [UserController::class, 'certificate']);
    Route::get('download-certificate', [UserController::class, 'downloadCertificate']);
    Route::get('user-details', [UserController::class, 'userDetails']);
    Route::post('submit-next/{id}', [UserController::class, 'submitAndNext']);
    Route::get('mcq/{id}/{name}', [UserController::class, 'mcq']);
});

// ---------------------- ADMIN ----------------------

Route::view('admin-login', 'admin-login');
Route::post('admin-login', [AdminController::class, 'login']);

Route::middleware('CheckAdminAuth')->group(function () {
    Route::get('dashboard',[AdminController::class,'dashboard']);
    Route::get('admin-categories', [AdminController::class, 'categories']);
    Route::get('admin-logout', [AdminController::class, 'logout']);
    Route::post('add-category', [AdminController::class, 'addCategory']);
    Route::get('category/delete/{id}', [AdminController::class, 'deleteCategory']);
    Route::get('add-quiz', [AdminController::class, 'addQuiz']);
    Route::post('add-mcq', [AdminController::class, 'addMCQs']);
    Route::get('end-quiz', [AdminController::class, 'endQuiz']);
    Route::get('show-quiz/{id}/{quizName}', [AdminController::class, 'showQuiz']);
    Route::get('quiz-list/{id}/{category}', [AdminController::class, 'quizList']);
});

Route::get('/start-quiz/{id}/{slug}', [UserController::class, 'startQuiz'])
    ->middleware('auth');

Route::get('/user-login-quiz', function () {
    Session::put('quiz-url', url()->previous());
    return redirect()->route('login');
})->name('user.login.quiz');

Route::get('/user-signup-quiz', function () {
    Session::put('quiz-url', url()->previous());
    return redirect()->route('register');
})->name('user.signup.quiz');

Route::get('/quiz-list/{categoryId?}/{categoryName?}', [UserController::class, 'quizList'])
    ->name('user.quiz.list');



Route::get('/quiz-list-user/{id}/{category}', [UserController::class, 'quizListUser'])
    ->name('user.quiz.list');
Route::get('/mcq/{id}/{name}', [UserController::class, 'mcq'])
    ->name('mcq');
