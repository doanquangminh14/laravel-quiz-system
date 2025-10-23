<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MCQ Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        /* Tùy chỉnh trạng thái input radio khi được chọn */
        input[type="radio"]:checked + span {
            font-weight: 700; /* bold */
            color: #166534; /* green-800 */
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    
    <x-user-navbar></x-user-navbar>
    
    @if(session('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-center font-medium shadow-sm">
            <p>{{ session('message') }}</p>
        </div>
    @endif
    
    <div class="flex flex-col items-center min-h-screen pt-10 px-4 sm:px-6 lg:px-8">
        
        <div class="w-full max-w-xl text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
                {{ $quizName }}
            </h1>
            <h2 class="text-xl font-bold text-green-600">
                Câu hỏi <span class="text-2xl">{{ session('currentQuiz')['currentMcq'] }}</span> / {{ session('currentQuiz')['totalMcq'] }}
            </h2>
        </div>
        
        <div class="p-6 sm:p-8 bg-white shadow-2xl rounded-xl w-full max-w-xl border border-gray-200">
            
            <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4 border-gray-100">
                {{ $mcqData->question }}
            </h3>
            
            <form action="/submit-next/{{$mcqData->id}}" class="space-y-4" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$mcqData->id}}">
                
                @php 
                    $options = ['a' => $mcqData->a, 'b' => $mcqData->b, 'c' => $mcqData->c, 'd' => $mcqData->d];
                    $i = 1;
                @endphp

                @foreach ($options as $key => $value)
                <label for="option_{{ $i }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer 
                              hover:bg-blue-50 transition duration-150 shadow-md has-[:checked]:border-green-500 has-[:checked]:bg-green-50"
                >
                    <input 
                        id="option_{{ $i }}" 
                        class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300" 
                        type="radio" 
                        value="{{ $key }}" 
                        name="option"
                    >
                    <span class="text-gray-900 pl-4 text-base font-medium">{{ $value }}</span>
                </label>
                @php $i++; @endphp
                @endforeach
                
                <div class="pt-6">
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white font-bold text-lg rounded-lg px-4 py-3 
                               hover:bg-blue-700 transition duration-200 shadow-xl"
                    >
                        Nộp Câu Trả Lời & Câu Tiếp Theo
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <x-footer-user></x-footer-user>
</body>
</html>