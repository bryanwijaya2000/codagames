@extends('main')
@section('title', 'CodaGames - Upload Game')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    <?php
        $loggedIn = session('loggedIn');
        $user = App\Models\Users::find($loggedIn['id']);
    ?>
    <div class="blocked" style="display:none">
        <input id="blocked" type="hidden" value="{{ $user->blocked }}">
    </div>
    <div class="container" style="height:410px;margin-top:7.9%">
        <div class="form-outer" style="margin-top:5%">
            <div class="upload-game-title" style="height:20%">Upload A Game</div>
            <form class="upload-game-form" enctype="multipart/form-data" method="post" action="/doUploadGame">
                @csrf
                Name: <input type="text" id="name" name="name" value="" placeholder="Name">
                <div id="name-error"></div>
                Image: <input type="file" id="image" name="image" accept="image/*">
                <div id="image-error"></div>
                File: <input type="file" id="file" name="file" accept=".html">
                <div id="file-error"></div>
                <br>
                <input type="submit" id="upload-game-btn" value="Upload">
            </form>
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
        $('.upload-game-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/doUploadGame',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function() {
                    $('#name').val('');
                    $('#image').val('');
                    $('#file').val('');
                    $('#name-error').text('');
                    $('#image-error').text('');
                    $('#file-error').text('');
                    alert('Game uploaded successfully !');
                },
                error: function(error) {
                    $('#name-error').text('');
                    $('#image-error').text('');
                    $('#file-error').text('');
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
