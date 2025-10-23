<nav class="bg-white shadow-md px-4 py-3">
  <div class="flex justify-between items-center">
    <div class="text-2xl text-green-900 hover:text-blue-500 cursor-pointer">
      Quiz System
    </div>

    <div class="space-x-4">
      <a class="text-green-900 hover:text-blue-500" href="/">Home</a>
      <a class="text-green-900 hover:text-blue-500" href="/categories-list">Categories</a>
      <a class="text-green-900 hover:text-blue-500" href="/leaderboard">Leaderboard</a>

      @auth
        <a class="text-green-900 hover:text-blue-500" href="/user-details">
          Welcome, {{ Auth::user()->name }}
        </a>
        <form action="{{ route('logout') }}" method="POST" class="inline">
          @csrf
          <button type="submit" class="text-green-900 hover:text-blue-500">Logout</button>
        </form>
      @else
        <a class="text-green-900 hover:text-blue-500" href="{{ route('login') }}">Login</a>
        <a class="text-green-900 hover:text-blue-500" href="{{ route('register') }}">Signup</a>
      @endauth

      <a class="text-green-900 hover:text-blue-500" href="/admin-logout">Blog</a>
    </div>
  </div>
</nav>
