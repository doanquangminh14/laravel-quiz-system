<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    
    <x-navbar name="{{$name}}"></x-navbar>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-l-4 border-green-600 pl-3">
                Danh Sách Người Dùng 🌿
            </h1>

            <div class="bg-white shadow-lg rounded-xl overflow-hidden ring-1 ring-gray-200">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-green-100">
                        
                        <thead class="bg-green-500">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider w-16 rounded-tl-xl">
                                    S. No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider rounded-tr-xl">
                                    Email
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($users as $key => $user)
                            <tr class="{{ $loop->even ? 'bg-white' : 'bg-green-50' }} hover:bg-green-100 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $key + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-green-100 bg-green-50 text-right">
                    {{$users->links()}}
                </div>
                </div>
            </div>
    </div>
</body>
</html>