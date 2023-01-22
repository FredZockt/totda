@extends('layouts.app')

@section('content')
@if (session()->has('status'))
    <div class="alert alert-{{ session()->get('status_type') }}">
        {{ session()->get('status') }}
    </div>
@endif
<div class="inventory-grid">
    @for ($row = 0; $row < 4; $row++)
        @for ($col = 0; $col < 8; $col++)
            @php
                $index = $col + ($row * 8);
                $item = $slots[$index] ?? null;
            @endphp
            <div class="cell {{ $item ? 'occupied' : '' }}">
                @if ($item)
                    <div class="item">
                        <div class="item-image">
                            <img src="{{ $item->good_name }}.png" alt="{{ $item->good_name }}"/>
                        </div>
                        <div class="item-info">
                            <p>{{ $item->good_name }}</p>
                            <p>Quantity: {{ $item->quantity }} / {{ $item->max_stack }}</p>
                            <p class="price text-{{ $item->price < $item->base_price * 0.8 ? 'warning' : ($item->price > $item->base_price * 1.19 ? 'success' : 'normal') }}">Price: {{ $item->price }}</p>
                        </div>
                        <div class="item-action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sellModal{{$index}}">
                                Sell
                            </button>
                            <form action="{{ route('inventory.delete', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                        <div class="modal fade" id="sellModal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$index}}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel{{$index}}">Sell {{ $item->good_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <input type="submit" value="Sell" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endfor
    @endfor
</div>
@endsection
