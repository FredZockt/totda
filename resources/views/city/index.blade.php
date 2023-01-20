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
                <div class="card-header">Welcome to {{$city->name}}</div>

                <div class="card-body">
                    @if($walkFlag)
                        <div class="alert alert-warning">You're still on the way</div>
                    @endif
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Building</th>
                            <th>Product</th>
                            <th>Short</th>
                            <th>Mid</th>
                            <th>Long</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($buildings as $building)
                            <tr>
                                <td>{{ $building->name }}</td>
                                <td>{{ $building->good_name }}</td>
                                <td>{{ $building->short_job }}</td>
                                <td>{{ $building->mid_job }}</td>
                                <td>{{ $building->long_job }}</td>
                                <td>
                                    <a href="/work/{{ $building->id }}" class="btn btn-primary">visit</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
