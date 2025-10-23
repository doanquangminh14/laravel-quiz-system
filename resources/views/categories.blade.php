<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categories Page</title>
    @vite('resources/css/app.css') 
</head>
<body class="bg-gray-50 font-sans">
    
    <x-navbar name="{{$name}}"></x-navbar>

    @if(session('category'))
        <div class="bg-green-500 text-white p-3 text-center font-medium shadow-md transition duration-300">
            {{ session('category') }}
        </div>
    @endif

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            
            <div class="lg:w-1/3 w-full">
                <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6 border-b pb-3 border-blue-100">
                        Thêm Danh Mục Mới
                    </h2>
                    <form action="/add-category" method="post" class="space-y-6">
                        @csrf
                        <div>
                            <label for="category-name" class="sr-only">Tên Danh Mục</label>
                            <input 
                                type="text" 
                                id="category-name"
                                placeholder="Nhập tên danh mục..." 
                                name="category"
                                class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                value="{{ old('category') }}"
                            >
                            @error('category')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div> 
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 hover:bg-blue-700 transition duration-200 shadow-md hover:shadow-lg"
                        >
                            Thêm Danh Mục
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:w-2/3 w-full">
                <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-200">
                    
                    <h1 class="text-2xl font-bold text-gray-800 p-6 border-b border-gray-100">
                        Danh Sách Danh Mục 📑
                    </h1>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">
                                        S.No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Creator
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($categories as $category)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $category->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $category->creator }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="category/delete/{{ $category->id }}" class="text-red-500 hover:text-red-700 transition" title="Xóa">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z"/></svg>
                                            </a>
                                            <a href="quiz-list/{{$category->id}}/{{$category->name}}" class="text-blue-500 hover:text-blue-700 transition" title="Xem danh sách Quiz">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>