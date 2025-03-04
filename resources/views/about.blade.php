@extends('template')
@section('title', 'CodaGames - About')
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
    <div class="container" style="height:383px">
        <div class="about">
            <div class="about-title">About</div>
            <div class="about-content">
                CodaGames is a website where users can explore, play and have fun
                <br>
                with games. Users can also upload their own games which then can
                <br>
                also be played by other users.
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
    <div class="footer" style="margin-top:40%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection
