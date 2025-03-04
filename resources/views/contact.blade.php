@extends('template')
@section('title', 'CodaGames - Contact')
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
        <div class="contact">
            <div class="contact-title">Contact</div>
            <div class="contact-content">
                Email: allisprogramming12789@gmail.com
                <br>
                Instagram: allisprogramming12789
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
