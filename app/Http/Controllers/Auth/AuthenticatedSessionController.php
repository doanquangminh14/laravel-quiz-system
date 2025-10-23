<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Quiz;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    // Nếu có lưu quiz-url (khi login từ trang quiz)
    if (session()->has('quiz-url')) {
        $redirectUrl = session('quiz-url');
        session()->forget('quiz-url'); // Xóa sau khi dùng

        // 🧭 Tự động chuyển sang trang làm quiz
        // ví dụ: http://127.0.0.1:8000/mcq/{id}/{quizName}
        if (str_contains($redirectUrl, '/start-quiz/')) {
            // Lấy ID quiz và tên quiz từ URL
            $parts = explode('/', $redirectUrl);
            $quizId = $parts[count($parts) - 2] ?? null;
            $quizName = $parts[count($parts) - 1] ?? null;

            if ($quizId && $quizName) {
                return redirect("/mcq/$quizId/$quizName")
                    ->with('message-success', 'Đăng nhập thành công! Bắt đầu làm quiz nhé!');
            }
        }

        // Nếu không match pattern, quay lại trang đó
        return redirect($redirectUrl)->with('message-success', 'Đăng nhập thành công!');
    }

    // Mặc định nếu login bình thường (không từ quiz)
    return redirect('/')->with('message-success', 'Đăng nhập thành công!');
}

/**
 * Đăng xuất tài khoản hiện tại.
 */
public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('message-success', 'Đăng xuất thành công!');
}



public function create(): View
{
    return view('auth.login');
}

}
