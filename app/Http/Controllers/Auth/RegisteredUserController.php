<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));
    Auth::login($user);

    // Nếu có quiz-url trong session
    if (session()->has('quiz-url')) {
        $redirectUrl = session('quiz-url');
        session()->forget('quiz-url');

        if (str_contains($redirectUrl, '/start-quiz/')) {
            $parts = explode('/', $redirectUrl);
            $quizId = $parts[count($parts) - 2] ?? null;
            $quizName = $parts[count($parts) - 1] ?? null;

            if ($quizId && $quizName) {
                return redirect("/mcq/$quizId/$quizName")
                    ->with('message-success', 'Đăng ký thành công! Bắt đầu làm quiz nhé!');
            }
        }

        return redirect($redirectUrl)->with('message-success', 'Đăng ký thành công!');
    }

    // Nếu không có quiz-url thì về trang chủ
    return redirect('/')->with('message-success', 'Đăng ký thành công!');
}

public function create(): View
{
    return view('auth.register');
}
}
