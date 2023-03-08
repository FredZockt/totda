@extends('layouts.app')
@section('title')
    Search
@stop
@section('description')
    You lost something? Maybe you can find it here.
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
            <div class="card-header">Search</div>

            <div class="card-body">
                <form action="/search/do" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="search_term">Search for Traders:</label>
                        <input type="text" class="form-control" id="search_term" name="search_term" required placeholder="Search..." value="{{$search_term ? $search_term : ''}}">
                    </div>
                    <input type="submit" value="Submit" class="btn mt-4">
                </form>
            </div>
        </div>
        @if($results)
        <div class="card mt-4">
            <div class="card-header">Your results:</div>
            <div class="card-body">
                @if($results->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($results as $result)
                            <li class="">{{ $result->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-warning mb-0">No results found...</div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
