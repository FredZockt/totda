@extends('layouts.app')
@section('title')
    Market of {{ $city->name }}
@stop
@section('description')
    Here you can buy items from {{ $city->name }}. BEst of luck and always good prices!
@stop
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

                <div class="card-body table-responsive">
                    @if($walkFlag)
                        <div class="alert alert-warning">You're still on the way</div>
                    @endif
                    <table class="table market">
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
                                <td>
                                    <div class="d-flex align-items-center">
                                    <span class="mini-icon__wrapper me-3">
                                        <img class="mini-icon" loading="lazy" src="{{ asset('assets/images/png_mini/'. $good->name .'.png') }}" alt="">
                                    </span>
                                    {{ $good->name }}
                                    </div>
                    
                                </td>
                                <td>{{ number_format($good->quantity, 0, ',', '.') }}</td>
                                <td>{{ number_format($good->price, 2, ',', '.') }}</td>
                                <td>{{ number_format($good->price + ($city->tax_rate * $good->price), 2, ',', '.') }}</td>
                                <td>{{ $good->max_stack }}</td>
                                <td>
                                    <button type="button" class="btn" data-toggle="modal" data-target="#sellModal{{$index}}">
                                        Buy
                                    </button>
                                    <div class="modal fade" id="sellModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$index}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel{{$index}}">Sell {{ $good->name }}</h5>
                                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
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
                                                        <button type="button" class="btn" data-dismiss="modal">Close</button>
                                                        <input type="submit" value="Buy" class="btn">
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
