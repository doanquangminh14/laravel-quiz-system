<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreQuizUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('login') || $request->is('register')) {
            // Nếu trước đó user đến từ trang quiz thì lưu lại
            if (url()->previous() && str_contains(url()->previous(), 'start-quiz')) {
                session(['quiz-url' => url()->previous()]);
            }
        }

        return $next($request);
    }
}
