@extends('layouts.app')

@section('content')
    @if (session()->has('status'))
        <div class="alert alert-{{ session()->get('status_type') }}">
            {{ session()->get('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>

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
        </div>
    </div>
@endsection
