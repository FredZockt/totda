@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
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
            <div class="col-md-8 pt-4">
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $index => $city)
                                    <tr>
                                        <td>{{ ($index + 1) }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->tax_rate }}</td>
                                        <td>{{ $city->id == auth()->user()->current_city_id ? '-' : number_format($city->distanceToInKm, 2) }}</td>
                                        <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsReadable }}</td>
                                        <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsDate }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
