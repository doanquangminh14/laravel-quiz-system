<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">
    
    <!-- Card Container Chính -->
    <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-2xl w-full max-w-sm border-t-8 border-blue-600">
        
        <!-- Tiêu đề -->
        <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-2">
            Quản Trị Viên
        </h2>
        <p class="text-sm text-gray-500 mb-8 text-center">
            Đăng nhập vào Hệ thống Quiz
        </p>
        
        <!-- Hiển thị lỗi chung (nếu có lỗi xác thực user) -->
        @error('user')
            <div class="p-3 mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md">
                {{ $message }}
            </div> 
        @enderror
        
        <form action="/admin-login" method="post" class="space-y-6">
            @csrf

            <!-- Trường Tên Admin -->
            <div>
                <label for="admin-name" class="block text-sm font-medium text-gray-700 mb-1">Tên Admin</label>
                <input 
                    type="text" 
                    id="admin-name"
                    placeholder="Nhập tên Admin..." 
                    name="name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                >
                @error('name')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div> 
                @enderror
            </div>

            <!-- Trường Mật khẩu -->
            <div>
                <label for="admin-password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <input 
                    type="password" 
                    id="admin-password"
                    placeholder="Nhập mật khẩu..." 
                    name="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                >
                @error('password')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div> 
                @enderror
            </div>

            <!-- Nút Đăng nhập -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white font-bold text-lg rounded-lg px-4 py-3 
                       hover:bg-blue-700 transition duration-200 shadow-lg mt-6"
            >
                Đăng Nhập
            </button>
        </form>
    </div>
</body>
</html>