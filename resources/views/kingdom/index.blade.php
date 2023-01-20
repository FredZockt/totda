@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Kingdom</div>

                    <div class="card-body">
                        <p>{{ $kingdom->name }}</p>
                        <p>{{ $kingdom->gold }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 pt-4">
                <div class="card">
                    <div class="card-header">Kingdom's cities</div>

                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td>Name</td>
                                    <td>Tax Rate</td>
                                    <td>Distance</td>
                                    <td>Time</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $index => $city)
                                    <tr>
                                        <td>{{ ($index + 1) }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->tax_rate }}</td>
                                        <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToInKm }}</td>
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
