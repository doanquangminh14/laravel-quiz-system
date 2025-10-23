<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz System Dashboard Page</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    
    <x-user-navbar></x-user-navbar> 
    
    <div class="flex flex-col min-h-screen items-center pt-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl w-full">

            @if(session('message-success'))
                <div class="p-3 mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm w-full mx-auto max-w-2xl">
                    <p class="font-semibold">{{ session('message-success') }}</p>
                </div>
            @endif

            <h1 class="text-4xl sm:text-5xl font-extrabold text-center text-gray-800 mb-8 mt-4">
                Kiểm tra kiến thức của bạn! 🧠
            </h1>
            
            <div class="w-full mx-auto max-w-2xl mb-12">
                <form action="search-quiz" method="get" class="relative shadow-xl rounded-xl">
                    <input 
                        class="w-full px-6 py-4 text-gray-700 border-2 border-green-300 
                                rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 placeholder-gray-500" 
                        type="text" 
                        name="search" 
                        placeholder="Tìm kiếm Quiz hoặc Danh mục..." 
                    />
                    <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-green-600 hover:text-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                    </button>
                </form>
            </div>
            
            <div class="w-full mx-auto max-w-4xl mb-12">
                <h2 class="text-3xl font-bold text-gray-700 text-center mb-6 border-b pb-2 border-green-200">
                    Danh Mục Nổi Bật 🚀
                </h2>
                
                <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        
                        <thead class="bg-green-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                    S. No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tên Danh Mục
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                    Số Quiz
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                                    Hành Động
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($categories as $key=>$category)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-green-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{$key+1}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{$category->name}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">
                                    {{$category->quizzes_count}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <a href="user-quiz-list/{{$category->id}}/{{str_replace(' ','-',$category->name)}}" class="text-green-600 hover:text-green-800 font-medium transition duration-150" title="Xem danh sách Quiz">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor" class="inline-block"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-full mx-auto max-w-4xl mb-20">
                <h2 class="text-3xl font-bold text-gray-700 text-center mb-6 border-b pb-2 border-green-200">
                    Quiz Hàng Đầu 🔥
                </h2>
                
                <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-100">
                    <ul class="divide-y divide-gray-200">
                        @foreach($quizData as $item)
                        <li class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} p-4 sm:p-5 hover:bg-green-50 transition duration-150 flex justify-between items-center">
                            
                            <p class="text-base font-semibold text-gray-900 w-full pr-4">
                                {{ $item->name }}
                            </p>
                            
                            <div class="flex-shrink-0">
                                <a 
                                    href="/start-quiz/{{$item->id}}/{{str_replace(' ','-',$item->name)}}" 
                                    class="text-white bg-green-500 hover:bg-green-600 font-bold 
                                           py-2 px-4 rounded-full transition duration-200 whitespace-nowrap shadow-md"
                                >
                                    Làm Quiz
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
    
    <x-footer-user></x-footer-user>
</body>
</html>