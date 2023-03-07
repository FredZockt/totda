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

                    <div class="card-body table-responsive">
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
                                    <td>{{ number_format($kingdom->gold, '0', ',', '.') }}</td>
                                    @if($king)
                                        @if($king->id == $user->id)
                                            <td>
                                                <form action="{{ route('kingdom.abdicate') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn">abdicate</button>
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
                                                            <button type="submit" class="btn">withdraw</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('kingdom.apply') }}" method="POST">
                                                            @csrf
                                                            <button {{ $user->gold < 5000 || $application ? 'disabled=disabled' : '' }} type="submit" class="btn">apply</button>
                                                            @if(count($applicants) > 0)
                                                                or
                                                                <button type="button" data-toggle="modal" data-target="#voteModal" class="btn">vote</button>
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
                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
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
                                                        <input type="submit" value="vote" class="btn">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($king && $king->id == $user->id)
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header">Your Army</div>
                        <div class="card-body">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Attack</th>
                                    <th>Defense</th>
                                    <th>Cost</th>
                                    <th>Amount</th>
                                    <th>Hire</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($units as $unit)
                                    <tr>
                                        <td>{{$unit->name}}</td>
                                        <td>{{$unit->attack}}</td>
                                        <td>{{$unit->defense}}</td>
                                        <td>{{$unit->cost}}</td>
                                        @foreach($troops as $troop)
                                            @if($unit->id == $troop->unit_id)
                                                <td>{{$troop->amount ? $troop->amount : 0}}</td>
                                                <td>
                                                    <form action="" method="post">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group h-100">
                                                                    <input type="number" class="form-control d-block h-100" id="quantity" name="quantity" min="1" max="{{$kingdom->gold / $unit->cost}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <button type="submit" class="btn d-block w-100">Hire</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">Kingdom's cities</div>

                    <div class="card-body table-responsive">
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
                                        <td>{{ number_format($city->tax_rate, 2, ',', '.') }}</td>
                                        @if(!auth()->user()->job_id)
                                            <td>{{ auth()->user()->current_city_id && $city->id == auth()->user()->current_city_id ? '-' : number_format($city->distanceToInKm, 2, ',', '.') }}</td>
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
                                                    <button type="submit" class="btn">visit</button>
                                                </form>
                                                @if($king && $user->id == $king->id)
                                                    <button type="button" data-toggle="modal" data-target="#manageModal{{$index}}" class="btn">manage</button>
                                                    <div class="modal fade" id="manageModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="manageModalLabel{{$index}}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="manageModalLabel{{$index}}">Manage {{$city->name}}</h5>
                                                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body table-responsive">
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
                                                                                        <button type="submit" class="btn">depose</button>
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
                                                                                                <button type="submit" class="btn">appoint</button>
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
                                                                    <button type="button" class="btn" data-dismiss="modal">Close</button>
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
