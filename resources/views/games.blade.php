@extends('template')
@section('title', 'CodaGames - Games')
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
    <div class="container" style="height:953px;overflow-y:scroll">
        <div class="games-title" style="height:5%">Games</div>
        <br>
        <div class="form-outer" style="width:100%;height:5%;margin-top:0%;margin-left:0%">
            <form class="search-games-form" method="get" action="/admin/searchGames">
                <input type="text" id="search-game-name" value="" placeholder="Search games...">
                <input type="submit" id="search-game-btn" value="Search">
            </form>
        </div>
        <table class="games-list">
            <?php
                $gamesCnt = count($approvedGames);
                $rowCnt = ceil($gamesCnt / 4);
            ?>
            @for ($i = 0; $i < $rowCnt; $i++)
                <tr>
                    @for ($j = 0; $j < 4; $j++)
                        <?php
                            $index = $i * 4 + $j;
                        ?>
                        @if ($index <= $gamesCnt - 1)
                            <?php
                                $ag = $approvedGames[$index];
                            ?>
                            <td>
                                <div>
                                    <img src="{{ asset('storage/images') }}/{{ $ag['image'] }}"/>
                                    <div><a href="{{ asset('storage/games') }}/{{ $ag['file'] }}">{{ $ag['name'] }}</a></div>
                                </div>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </table>
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
        $('.search-games-form').submit(function(e) {
            e.preventDefault();
            let gameName = $('#search-game-name').val();
            let url = `/games/searchGames?name=${gameName}`;
            $.ajax({
                url: url,
                success: function (result) {
                    $('.games-list').html(result);
                }
            });
        });
    </script>
@endsection
@section('footer')
    <div class="footer" style="margin-top:85%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection
