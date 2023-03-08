@extends('layouts.app')
@section('title')
    {{ $building->owner ? $building->owner->name : '' }} {{ $building->name }} {{ $building->owner ? '(Level: ' . $building->level . ')' : '' }}
@stop
@section('description')
    Start your work in {{ $building->owner ? $building->owner->name : '' }} {{ $building->name }} {{ $building->owner ? '(Level: ' . $building->level . ')' : '' }} and gather resources for trading.
@stop
@section('content')
    @if (session()->has('status'))
        <div class="alert alert-{{ session()->get('status_type') }}">
            {{ session()->get('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $building->owner ? $building->owner->name : '' }} {{ $building->name }} {{ $building->owner ? '(Level: ' . $building->level . ')' : '' }}</div>

                <div class="card-body table-responsive">
                    @if($walkFlag)
                        <div class="alert alert-warning">You're still on the way</div>
                    @endif
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Short</th>
                            <th>Mid</th>
                            <th>Long</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $building->short_job }}</td>
                            <td>{{ $building->mid_job }}</td>
                            <td>{{ $building->long_job }}</td>
                        </tr>
                        @if(!$walkFlag && !$workFlag)
                        <tr>
                            <td>
                                <form action="/work/{{ $building->id }}/start/1" method="POST">
                                    @csrf
                                    <button type="submit" class="btn">start</button>
                                </form>
                            </td>
                            <td>
                                <form action="/work/{{ $building->id }}/start/2" method="POST">
                                    @csrf
                                    <button type="submit" class="btn">start</button>
                                </form>
                            </td>
                            <td>
                                <form action="/work/{{ $building->id }}/start/3" method="POST">
                                    @csrf
                                    <button type="submit" class="btn">start</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if($building->user_id == $user->id)
                <div class="card mt-4">
                    <div class="card-header">Options</div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                @if($user->gold >= $building->level * 25000)
                                <div class="col">
                                    <form action="/building/level/{{$building->id}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn">Level Up</button>
                                    </form>
                                </div>
                                @endif
                                @if(!$running_auction)
                                <div class="col text-end">
                                    <button type="button" class="btn" data-toggle="modal" data-target="#sellModal">Sell</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(!$running_auction)
                <div class="modal fade" id="sellModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Sell building</h5>
                                <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="/building/sell/{{$building->id}}" method="POST">
                                <div class="modal-body">
                                    @csrf
                                    <div class="alert alert-danger">Warning!</div>
                                    <p>
                                        You are about to release you building for auction. Click the sell button below only if you are absolutely sure you know what you are doing.
                                    </p>
                                    <input type="submit" value="Sell" class="btn btn-danger">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
@endsection
