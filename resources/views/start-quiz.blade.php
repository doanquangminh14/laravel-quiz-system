<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ str_replace('-',' ', $quizName) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    
    <x-user-navbar></x-user-navbar>
    
    @if(session('message-success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-center font-medium shadow-sm">
            {{ session('message-success') }}
        </div>
    @endif
    
    <div class="flex flex-col items-center justify-center min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-2xl w-full max-w-xl text-center border-t-8 border-green-500">
            
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-800 mb-4">
                {{ str_replace('-',' ', $quizName) }}
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Bài kiểm tra này có **{{ $quizCount }} câu hỏi**. Hãy tự tin thử sức!
            </p>
            
            <div class="bg-green-50 p-6 rounded-xl border border-green-200 mb-10">
                <p class="text-sm font-semibold text-green-700">
                    Lưu ý: Không giới hạn số lần thực hiện bài Quiz này.
                </p>
            </div>
            
            <h2 class="text-3xl font-bold text-green-600 mb-12">
                Chúc bạn may mắn! 🍀
            </h2>

            <div class="space-y-4">
                @if(Auth::check() && session('firstMCQ'))
                    <a 
                        href="/mcq/{{ session('firstMCQ')->id.'/'.$quizName }}" 
                        class="block w-full bg-blue-600 text-white font-bold text-xl rounded-lg px-6 py-4 
                               hover:bg-blue-700 transition duration-200 shadow-lg transform hover:scale-105"
                    >
                        BẮT ĐẦU QUIZ NGAY!
                    </a>
                @else
                    <a 
                        href="/user-signup-quiz" 
                        class="block w-full bg-green-500 text-white font-bold text-lg rounded-lg px-6 py-3 
                               hover:bg-green-600 transition duration-200 shadow-md"
                    >
                        Đăng Ký để Bắt Đầu Quiz
                    </a>
                    <a 
                        href="/user-login-quiz" 
                        class="block w-full bg-gray-200 text-gray-800 font-bold text-lg rounded-lg px-6 py-3 
                               hover:bg-gray-300 transition duration-200"
                    >
                        Đăng Nhập để Bắt Đầu Quiz
                    </a>
                @endif
            </div>

        </div>
    </div>
    
</body>
</html>