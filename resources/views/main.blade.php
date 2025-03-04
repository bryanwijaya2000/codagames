<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href={{ asset('css/style.css') }} type="text/css">
</head>
<body>
    <div class="banner">
        @yield('back')
        <div class="title">CodaGames</div>
        @if (session('loggedIn'))
            <?php
                $user = session('loggedIn');
            ?>
            @if ($user['username'] == 'admin2000')
                <div class="auth" style="width:7%">
                    <a href="/doLogout">Logout</a>
                </div>
            @else
                <div class="auth" style="width:23%">
                    <a href="/uploadGame">Upload Game</a>
                    |
                    <a href="/account">Account</a>
                    |
                    <a href="/doLogout">Logout</a>
                </div>
            @endif
        @else
            <div class="auth">
                <a href="/login">Login</a>
                |
                <a href="/register">Register</a>
            </div>
        @endif
    </div>
    @yield('navbar')
    @yield('content')
    @yield('footer')
</body>
</html>
