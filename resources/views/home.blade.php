@extends('template')
@section('title', 'CodaGames - Home')
@section('content')
    @if (session('loggedIn'))
        <?php
            $loggedIn = session('loggedIn');
            $user = App\Models\Users::find($loggedIn['id']);
        ?>
        <div class="blocked" style="display:none">
            <input id="blocked" type="hidden" value="{{ $user->blocked }}">
        </div>
    @endif
    <div class="container" style="height:953px">
        <img class="games" src={{ asset('img/games.jpg') }}>
        <div class="description">Explore, Play and Upload your own games for free !</div>
        <div class="goals">
            <div class="mission">
                <div class="mission-title">Mission</div>
                <div class="mission-content">
                    Our mission is to allow users to explore, play, and
                    <br>
                    have fun by playing games
                </div>
            </div>
            <div class="vision">
                <div class="vision-title">Vision</div>
                <div class="vision-content">
                    Our vision is to become the largest gaming platform
                    <br>
                    in the world while we grow
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        let blocked = $('.blocked');
        if (blocked) {
            setInterval(() => {
                $('.blocked').load(`${window.location.href} .blocked > *`);
                let blockedVal = parseInt($('#blocked').val());
                if (blockedVal == 1) {
                    window.location.href = '/doLogout';
                    alert('You have been blocked');
                }
            }, 2000);
        }
    </script>
@endsection
@section('footer')
    <div class="footer" style="margin-top:85%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection
