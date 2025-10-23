<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details Page</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    
    <x-user-navbar></x-user-navbar> 
    
    <div class="flex flex-col min-h-screen items-center pt-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">

            <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-10 border-b pb-3 border-green-100">
                Các Bài Quiz Đã Thực Hiện 📜
            </h1>

            <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-200">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        
                        <thead class="bg-green-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                    S. No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tên Quiz
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                    Trạng Thái
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($quizRecord as $key=>$record)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-green-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{$key+1}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                    {{$record->name}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    @if($record->status == 2)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            ✅ Hoàn thành
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            ⏳ Chưa hoàn thành
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($quizRecord->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-gray-500 text-lg">
                                        Bạn chưa thực hiện bài Quiz nào!
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <x-footer-user></x-footer-user>
</body>
</html>