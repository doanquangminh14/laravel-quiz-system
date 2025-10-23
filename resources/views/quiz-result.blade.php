<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Quả Quiz</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    
    <x-user-navbar></x-user-navbar> 
    
    <div class="flex flex-col min-h-screen items-center pt-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">

            <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl text-center mb-10 border-t-8 border-green-500">
                
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-800 mb-4">
                    Kết Quả Quiz Của Bạn 🎉
                </h1>
                
                <div class="bg-green-50 p-6 rounded-lg mb-6">
                    <h1 class="text-3xl sm:text-4xl font-black text-green-700">
                        {{ $correctAnswers }} / {{ count($resultData) }} Đúng
                    </h1>
                    <p class="text-lg font-semibold text-gray-600 mt-2">
                        Đạt {{ round($correctAnswers * 100 / count($resultData), 2) }}%
                    </p>
                </div>

                @if($correctAnswers * 100 / count($resultData) > 70)
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-md shadow-sm mx-auto max-w-sm">
                         <a 
                            class="text-blue-600 font-bold block text-center hover:text-blue-800 transition duration-150" 
                            href="/certificate"
                        >
                            🎉 Xem và Tải Chứng Chỉ của bạn!
                        </a>
                    </div>
                @else
                     <p class="text-sm text-gray-500">
                        Cần đạt trên 70% để nhận chứng chỉ. Cố gắng lần sau nhé!
                    </p>
                @endif
            </div>

            <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 p-6 border-b border-gray-100">
                    Chi Tiết Kết Quả Theo Câu Hỏi
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                    S. No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nội Dung Câu Hỏi
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                    Kết Quả
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($resultData as $key=>$item)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{$key+1}}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                    {{$item->question}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-center">
                                    @if($item->is_correct)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            ✔ Đúng
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            ✘ Sai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <x-footer-user></x-footer-user>
</body>
</html>