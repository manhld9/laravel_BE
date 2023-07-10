<div class="navbar">
  <div class="navbar-inner">
    <a id="logo" href="/">Test</a>
    <ul class="nav">
      <li><a href="/">Home</a></li>
      @if (Auth::check())
        <li><a href="{{ route('signout') }}">LogOut</a></li>
      @else
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register-user') }}">Register</a></li>
      @endif
    </ul>
  </div>
</div>
