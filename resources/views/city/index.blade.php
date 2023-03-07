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

                <div class="card-body table-responsive">
                    <table class="table align-middle">
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
                                    <td>{{ number_format($city->tax_rate, 2, ',', '.') }}</td>
                                @endif
                                @else
                                <td>{{ number_format($city->tax_rate, 2, ',', '.') }}</td>
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
                                    <td class="d-flex align-items-center justify-content-between">
                                        <span>Application can send until {{ $vacancy->open_until }}</span>
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
            @if($canBuild && count($potentialResourceBuildings) > 0)
            <div class="card mt-4">
                <div class="card-header">
                    You can build a building
                </div>
                <div class="card-body">
                    <p>
                        You can build one building per city, even in cities that do not belong to your kingdom.
                        The building will cost 25.000 gold and will take 8 hours.
                        Attention: You can lose your building if:
                        <ul>
                            <li>The city is conquered by the other kingdom</li>
                        </ul>
                    </p>
                    <table class="table align-middle table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Building</th>
                            <th>Product</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($potentialResourceBuildings as $resource)
                            <tr>
                                <td>{{ $resource->name . '_factory' }}</td>
                                <td>{{ $resource->name }}</td>
                                <td>
                                    <form action="/city/build" method="post">
                                        @csrf
                                        <input type="hidden" name="resource_type" value="{{ $resource->id }}"/>
                                        <button type="submit" class="btn">build</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            <div class="card mt-4">
                <div class="card-header">Buildings to work in</div>

                <div class="card-body table-responsive">
                    @if($walkFlag)
                        <div class="alert alert-warning">You're still on the way</div>
                    @endif
                    <span>Buildings of {{$city->name}}</span>
                    <table class="table align-middle table-striped table-hover">
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
                        @foreach($systemBuildings as $building)
                            @if($building->user_id == null)
                            <tr>
                                <td>{{ $building->name }}</td>
                                <td>{{ $building->good_name }}</td>
                                <td>{{ $building->short_job }}</td>
                                <td>{{ $building->mid_job }}</td>
                                <td>{{ $building->long_job }}</td>
                                <td class="d-flex justify-content-end">
                                    <a href="/work/{{ $building->id }}" class="btn">visit</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                    @if($userBuildings->count() > 0)
                    <hr/>
                    <span>Buildings of Traders</span>
                    <table class="table align-middle table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Building</th>
                            <th>Level</th>
                            <th>Product</th>
                            <th>Short</th>
                            <th>Mid</th>
                            <th>Long</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userBuildings as $building)
                            @if($building->user_id != null)
                                <tr>
                                    <td>{{ $building->owner->name }} {{ $building->name }}</td>
                                    <td>{{ $building->level }}</td>
                                    <td>{{ $building->good_name }}</td>
                                    @if($building->active)
                                    <td>{{ $building->short_job }}</td>
                                    <td>{{ $building->mid_job }}</td>
                                    <td>{{ $building->long_job }}</td>
                                    <td>
                                        <a href="/work/{{ $building->id }}" class="btn">visit</a>
                                    </td>
                                    @else
                                        <td colspan="4" class="text-center">not yet ready</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            @if(count($auctions) > 0)
            <div class="card mt-4">
                <div class="card-header">Auctions in {{ $city->name }}</div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Building</th>
                            <th>Owner</th>
                            <th>Product</th>
                            <th>Highest bid</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($auctions as $index => $auction)
                        <tr>
                            <td>{{ $auction->building_name }}</td>
                            <td>{{ $auction->owner_name }}</td>
                            <td>{{ $auction->good_name }}</td>
                            <td {{$auction->building_user_id != $user->id ? '' : 'colspan="2"'}}>{{ number_format($auction->bid, 0, ',', '.') }}</td>
                            @if($auction->building_user_id != $user->id)
                                <td>
                                    <button type="button" class="btn" data-toggle="modal" data-target="#bidModal{{$index}}">
                                        Place bid
                                    </button>
                                    <div class="modal fade" id="bidModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="bidModalLabel{{$index}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="bidModalLabel{{$index}}">Bid for {{ $auction->building_name }}</h5>
                                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('city.auction') }}" method="POST">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="bid">Bid</label>
                                                            <input type="hidden" name="auction" value="{{$auction->id}}"/>
                                                            <input type="number" class="form-control" id="bid" name="bid" value="{{$auction->bid + 500}}" min="{{$auction->bid + 500}}" max="{{ $user->gold }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn">Place bid</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
