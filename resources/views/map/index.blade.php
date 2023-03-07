@extends('layouts.app')

@section('content')
    @if (session()->has('status'))
        <div class="alert alert-{{ session()->get('status_type') }}">
            {{ session()->get('status') }}
        </div>
    @endif
    <style>
        .map-row:after {
            content:" ";
            clear: both;
            display: block;
        }
    </style>
{{--    <div class="map">
        @for($x = 0; $x <= 100; $x++)
            <div class="map-row">
                @for($y = 0; $y <= 100; $y++)
                    <div data-x="{{$x}}" data-y="{{$y}}" style="width:5px;height:5px;background-color: #2d3748;float:left;">
                        @foreach($cities as $index => $city)
                            @if($city->x == $x && $city->y == $y)
                                <span data-toggle="modal" data-target="#cityModal{{$index}}" data-x="{{$city->x}}" data-y="{{$city->y}}" style="cursor: pointer;display: block; height:5px; width: 5px; background-color: {{$city->kingdom_id == 1 ? 'red' : 'blue'}};"></span>
                                <div class="modal fade" id="cityModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$index}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel{{$index}}">{{ $city->name }}</h5>
                                                <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Kingdom</th>
                                                        <th>Distance</th>
                                                        <th>Duration</th>
                                                        <th>Arrival at</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>{{ $city->kingdom }}</td>
                                                        @if(!auth()->user()->job_id)
                                                            <td>{{ auth()->user()->current_city_id && $city->id == auth()->user()->current_city_id ? '-' : number_format($city->distanceToInKm, 2) }}</td>
                                                            <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsReadable }}</td>
                                                            <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsDate }}</td>
                                                        @else
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                @if(!auth()->user()->job_id)
                                                    <form method="POST" action="/walk/{{$city->id}}">
                                                        @csrf
                                                        <input type="submit" value="visit" class="btn">
                                                    </form>
                                                @else
                                                    <div class="alert alert-info">You are currently working/walking</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endfor
            </div>

        @endfor
    </div>--}}

    <div class="map mt-1">
        @foreach($map as $indexX => $x)
            <div class="map-row">
                @foreach($x as $indexY => $y)
                    <div data-value="{{$y['value']}}" data-x="{{$indexX}}" data-y="{{$indexY}}" style="width:8px;height:8px;background-color: {{$y['bg']}};float:left;">
                        @foreach($cities as $index => $city)
                            @if($city->x == $indexX && $city->y == $indexY)
                                <span data-toggle="modal" data-target="#cityModal{{$index}}" data-x="{{$city->x}}" data-y="{{$city->y}}" style="cursor: pointer;display: block; height:8px; width: 8px; background-color: {{$city->kingdom_id == 1 ? 'red' : 'blue'}};"></span>
                                <div class="modal fade" id="cityModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$index}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel{{$index}}">{{ $city->name }}</h5>
                                                <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Kingdom</th>
                                                        <th>Distance</th>
                                                        <th>Duration</th>
                                                        <th>Arrival at</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>{{ $city->kingdom }}</td>
                                                        @if(!auth()->user()->job_id)
                                                            <td>{{ auth()->user()->current_city_id && $city->id == auth()->user()->current_city_id ? '-' : number_format($city->distanceToInKm, 2, ',', '.') }}</td>
                                                            <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsReadable }}</td>
                                                            <td>{{ $city->id == auth()->user()->current_city_id ? '-' : $city->distanceToAsDate }}</td>
                                                        @else
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">
                                                            <ul class="row ressource-list">
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                                <li class="col-6 ressource-list__item">
                                                                    <span class="d-flex align-items-center">
                                                                        <span class="mini-icon__wrapper me-3">
                                                                            <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/wool.png') }}" alt="">
                                                                        </span>
                                                                        item
                                                                    </span>
                                                                    <span>price</span>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                @if(!auth()->user()->job_id)
                                                    <form method="POST" action="/walk/{{$city->id}}">
                                                        @csrf
                                                        <input type="submit" value="visit" class="btn">
                                                    </form>
                                                @else
                                                    <div class="alert alert-info">You are currently working/walking</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
