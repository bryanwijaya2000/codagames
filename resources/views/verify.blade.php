@extends('main')
@section('title', 'CodaGames - Verification')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container" style="height:410px;margin-top:7.9%">
        <div class="form-outer" style="width:45%;margin-top:10%;margin-left:30%">
            <div class="verify-title">Enter the 4-digit code sent to your email</div>
            <br>
            <form class="verify-form" method="post" action="/doVerify">
                @csrf
                <input type="number" id="digit-1" value="" min="0" max="9" onkeydown="Check(1, event)" autofocus>
                <input type="number" id="digit-2" value="" min="0" max="9" onkeydown="Check(2, event)">
                <input type="number" id="digit-3" value="" min="0" max="9" onkeydown="Check(3, event)">
                <input type="number" id="digit-4" value="" min="0" max="9" onkeydown="Check(4, event)">
                <div id="code-error" style="margin-left:35%"></div>
                <br>
                <input type="submit" id="verify-btn" value="Verify">
            </form>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        function Check(p, event) {
            let key = event.key;
            if (key >= 0 && key <= 9) {
                $(`#digit-${p}`).val(key);
                $(`#digit-${p}`).blur();
                let next = p + 1;
                if (next <= 4) {
                    setTimeout(() => $(`#digit-${next}`).focus(), 1);
                }
            }
            else if (key == 'Backspace') {
                $(`#digit-${p}`).val('');
                $(`#digit-${p}`).blur();
                let prev = p - 1;
                if (prev >= 1) {
                    setTimeout(() => $(`#digit-${prev}`).focus(), 1);
                }
            }
        }
        $('.verify-form').submit(function(e) {
            e.preventDefault();
            let code = '';
            for (let i = 1; i <= 4; i++) {
                code += $(`#digit-${i}`).val();
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/doVerify',
                data: {
                    code: code
                },
                success: function(url) {
                    window.location = url;
                },
                error: function(error) {
                    alert(error.responseText);
                    $('#code-error').text('');
                    let response = $.parseJSON(error.responseText);
                    $('#code-error').text(response.errors['code'][0]);
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
