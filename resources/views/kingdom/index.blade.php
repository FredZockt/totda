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
                                    <th>King / Queen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $kingdom->name }}</td>
                                    <td>{{ $kingdom->gold }}</td>
                                    @if($king)
                                        @if($king->id == $user->id)
                                            <td>
                                                <form action="{{ route('kingdom.abdicate') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">abdicate</button>
                                                </form>
                                            </td>
                                        @else
                                            <td>{{ $king->name }}</td>
                                        @endif
                                    @else
                                        @if(!$vacancy)
                                            <td>-</td>
                                        @else
                                            @if($kingdom->id == $user->kingdom_id)
                                                <td>
                                                    @if($application)
                                                        <form action="{{ route('kingdom.apply.cancel') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">withdraw</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('kingdom.apply') }}" method="POST">
                                                            @csrf
                                                            <button {{ $user->gold < 5000 || $application ? 'disabled=disabled' : '' }} type="submit" class="btn btn-primary">apply</button>
                                                            @if(count($applicants) > 0)
                                                                or
                                                                <button type="button" data-toggle="modal" data-target="#voteModal" class="btn btn-primary">vote</button>
                                                            @endif
                                                        </form>
                                                    @endif
                                                    <p>Application can send until {{ $vacancy->open_until }}</p>
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
                    <div class="modal fade" id="voteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Vote for a new monarch</h5>
                                    <button type="button" class="close btn btn-secondary" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Votes</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($applicants as $index => $applicant)
                                            <tr>
                                                <td>{{ $applicant->name }}</td>
                                                <td>{{ $applicant->votings }}</td>
                                                <td>
                                                    <form action="{{ route('kingdom.vote', $applicant->user_id) }}" method="POST">
                                                        @csrf
                                                        <input type="submit" value="vote" class="btn btn-primary">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 pt-4">
                <div class="card">
                    <div class="card-header">Kingdom's cities</div>

                    <div class="card-body">
                        <table class="table table-hover table-striped">
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
                                                <form method="POST" action="/walk/{{$city->id}}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">visit</button>
                                                </form>
                                                @if($king && $user->id == $king->id)
                                                    <button type="button" data-toggle="modal" data-target="#manageModal{{$index}}" class="btn btn-primary">manage</button>
                                                    <div class="modal fade" id="manageModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="manageModalLabel{{$index}}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="manageModalLabel{{$index}}">Manage {{$city->name}}</h5>
                                                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($city->governor()->first())
                                                                        <table class="table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Rank</th>
                                                                                <th>Name</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>Governor</td>
                                                                                <td>{{$city->governor()->first()->name}}</td>
                                                                                <td>
                                                                                    <form method="POST" action="/city/depose/{{$city->id}}" class="d-inline">
                                                                                        @csrf
                                                                                        <button type="submit" class="btn btn-primary">depose</button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        @else
                                                                        @if($governor_applicants[$city->id]->count() > 0)
                                                                            <table class="table table-hover table-striped">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>Applicant</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($governor_applicants[$city->id] as $applicant)
                                                                                    <tr>
                                                                                        <td>{{$applicant->name}}</td>
                                                                                        <td>
                                                                                            <form method="POST" action="/city/appoint/{{$city->id}}/{{$applicant->user_id}}" class="d-inline">
                                                                                                @csrf
                                                                                                <button type="submit" class="btn btn-primary">appoint</button>
                                                                                            </form>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                            @else
                                                                            <p>No applications yet...</p>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
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
