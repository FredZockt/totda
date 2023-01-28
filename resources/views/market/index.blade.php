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
                <div class="card-header">Welcome to the {{$city->name}} market</div>

                <div class="card-body">
                    @if($walkFlag)
                        <div class="alert alert-warning">You're still on the way</div>
                    @endif
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Price net</th>
                            <th>Price gross</th>
                            <th>Stack size</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($goods as $index => $good)
                            <tr>
                                <td>{{ $good->name }}</td>
                                <td>{{ $good->quantity }}</td>
                                <td>{{ $good->price }}</td>
                                <td>{{ $good->price + ($city->tax_rate * $good->price) }}</td>
                                <td>{{ $good->max_stack }}</td>
                                <td>
                                    <form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sellModal{{$index}}">
                                            Buy
                                        </button>
                                    </form>
                                    <div class="modal fade" id="sellModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$index}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel{{$index}}">Sell {{ $good->name }}</h5>
                                                    <button type="button" class="close btn btn-secondary" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('market.buy', $good->id) }}" method="POST">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="quantity">Quantity</label>
                                                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="{{ $available_gold > ($good->price + ($city->tax_rate * $good->price)) * $good->max_stack ? $good->max_stack : ceil($available_gold / ($good->price + ($city->tax_rate * $good->price))) }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <input type="submit" value="Buy" class="btn btn-primary">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
