@extends('main')
@section('navbar')
    <div class="navbar-container">
        <ul class="navbar">
            <?php
                $loggedIn = session('loggedIn');
            ?>
            @if ($loggedIn == null)
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/games">Games</a></li>
            @else
                @if ($loggedIn['username'] != 'admin2000')
                    @if ($_SERVER['REQUEST_URI'] != '/account' && $_SERVER['REQUEST_URI'] != '/uploadedGames')
                        <li><a href="/">Home</a></li>
                        <li><a href="/about">About</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <li><a href="/games">Games</a></li>
                    @else
                        <li style="width:48%"><a href="/account">Account</a></li>
                        <li style="width:48%"><a href="/uploadedGames">Uploaded Games</a></li>
                    @endif
                @else
                    <li style="width:48%"><a href="/admin">Games</a></li>
                    <li style="width:48%"><a href="/admin/users">Users</a></li>
                @endif
            @endif
        </ul>
    </div>
@endsection
