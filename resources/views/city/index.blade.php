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
                <div class="card-header">Welcome to {{ $city->name }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>name</th>
                            <th>current tax rate</th>
                            <th>governor</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $city->name }}</td>
                            @if($governor)
                                @if($governor->id == $user->id)
                                    <td>
                                        <form action="/city/apply/tax" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <input type="number" step="0.01" class="w-25 form-control d-inline-block" id="rate" name="rate" min="0.01" max="5.00" required value="{{$city->tax_rate}}">
                                                <button type="submit" class="btn">save</button>
                                            </div>
                                        </form>
                                    </td>
                                    @else
                                    <td>{{ $city->tax_rate }}</td>
                                @endif
                                @else
                                <td>{{ $city->tax_rate }}</td>
                            @endif
                            @if($governor)
                                @if($governor->id == $user->id)
                                    <td>
                                        <form action="{{ route('city.abdicate') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn">abdicate</button>
                                        </form>
                                    </td>
                                @else
                                    <td>{{ $governor->name }}</td>
                                @endif
                            @else
                                @if(!$vacancy)
                                    <td>-</td>
                                @else
                                    @if($city->kingdom_id == $user->kingdom_id)
                                    <td>
                                        @if($application && $city->id == $application->city_id)
                                            <form action="{{ route('city.apply.cancel') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn">withdraw</button>
                                            </form>
                                        @else
                                            <form action="{{ route('city.apply') }}" method="POST">
                                                @csrf
                                                <button {{ $user->gold < 500 || $application ? 'disabled=disabled' : '' }} type="submit" class="btn">apply</button>
                                            </form>
                                        @endif
                                        <span>Application can send until {{ $vacancy->open_until }}</span>
                                    </td>
                                    @else
                                        <td>-</td>
                                    @endif
                                @endif
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">Buildings to work in</div>

                <div class="card-body">
                    @if($walkFlag)
                        <div class="alert alert-warning">You're still on the way</div>
                    @endif
                    <table class="table align-middle">
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
                                    <a href="/work/{{ $building->id }}" class="btn">visit</a>
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
