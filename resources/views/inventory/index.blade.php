@extends('layouts.app')

@section('content')
@if (session()->has('status'))
    <div class="alert alert-{{ session()->get('status_type') }}">
        {{ session()->get('status') }}
    </div>
@endif
<div class="row">
    @for ($row = 0; $row < 4; $row++)
        @for ($col = 0; $col < 8; $col++)
            @php
                $index = $col + ($row * 8);
                $item = $slots[$index] ?? null;
            @endphp
            @if ($item)
            <div class="col-auto">
                <div class="info-card">

                    <img class="info-card__image mb-3" src="{{asset('assets/images/'.$item->good_name.'.png')}}" alt="{{ $item->good_name }}">

                    <div class="info-card__content">
                        <h3>{{ $item->good_name }}</h3>

                        <div class="row">
                            <div class="col-6">Quantity: </div>
                            <div class="col-6 text-end">{{ $item->quantity }} / {{ $item->max_stack }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">Price: </div>
                            <div class="col-6 text-end price text-{{ $item->price < $item->base_price * 0.8 ? 'warning' : ($item->price > $item->base_price * 1.19 ? 'success' : 'normal') }}">{{ round($item->price, 2) }}</div>
                        </div>
                        <button data-toggle="modal" data-target="#sellModal{{$index}}" class="btn w-100 mb-2">Sell</button>
                        <form action="{{ route('inventory.delete', $item->id) }}" method="POST" class="w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-100">
                                Delete
                            </button>
                        </form>
                    </div>
                    <div class="modal fade" id="sellModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$index}}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel{{$index}}">Sell {{ $item->good_name }}</h5>
                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('inventory.sell', $item->id) }}" method="POST">
                                    <div class="modal-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="{{ $item->quantity }}" required>
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" data-dismiss="modal">Close</button>
                                        <input type="submit" value="Sell" class="btn">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        @endfor
    @endfor
</div>
@endsection
