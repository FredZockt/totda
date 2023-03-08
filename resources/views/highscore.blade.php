@extends('layouts.app')
@section('title')
    Highscore
@stop
@section('description')
    View the current highscores. Those are determined by the amount of gold a trader have.
@stop
@section('content')
<div class="table-responsive">
    <table class="highscore table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Gold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($players as $index => $player)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ number_format($player->gold, '0', ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
