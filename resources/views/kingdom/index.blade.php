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
                    <div class="card-header">Kingdom you belong to</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>current gold</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $kingdom->name }}</td>
                                    <td>{{ $kingdom->gold }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 pt-4">
                <div class="card">
                    <div class="card-header">Kingdom's cities</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Tax Rate</th>
                                    <th>Distance</th>
                                    <th>Duration</th>
                                    <th>Arrival</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $index => $city)
                                    <tr>
                                        <td>{{ ($index + 1) }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->tax_rate }}</td>
                                        @if(!auth()->user()->job_id)
                                            <td>{{ auth()->user()->current_city_id && $city->id == auth()->user()->current_city_id ? '-' : number_format($city->distanceToInKm, 2) }}</td>
                                            <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsReadable }}</td>
                                            <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsDate }}</td>
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        @endif
                                        @if($city->id != auth()->user()->current_city_id && !auth()->user()->job_id)
                                            <td>
                                                <form method="POST" action="/walk/{{$city->id}}">
                                                    @csrf
                                                    <input type="submit" value="visit" class="btn btn-primary">
                                                </form>
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
