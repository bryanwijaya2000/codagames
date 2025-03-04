@extends('main')
@section('title', 'CodaGames - Account Recovery')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    <div class="container" style="height:410px;margin-top:7.9%">
        <div class="form-outer" style="margin-top:9%">
            <div class="account-recovery-title">Account Recovery</div>
            <br>
            <form class="account-recovery-form" method="post" action="/checkEmail">
                @csrf
                Enter your email: <input type="text" id="email" name="email" value="" placeholder="Email" autofocus>
                <div id="email-error"></div>
                <br>
                <input type="submit" id="check-email-btn" value="Submit">
            </form>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        $('.account-recovery-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/checkEmail',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(url) {
                    window.location = '/verify';
                },
                error: function(error) {
                    $('#email-error').text('');
                    let response = $.parseJSON(error.responseText);
                    for (let key in response.errors) {
                        $(`#${key}-error`).text(response.errors[key][0]);
                    }
                }
            });
        });
    </script>
@endsection
@section('footer')
    <div class="footer" style="margin-top:40%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection
