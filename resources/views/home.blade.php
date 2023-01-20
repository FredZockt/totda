@extends('layouts.app')

@section('content')
<img class="header-image mb-5" src="{{asset('assets/images/bg.png')}}" alt="Traveler">
<div class="container">
    @if (session()->has('status'))
        <div class="alert alert-{{ session()->get('status_type') }}">
            {{ session()->get('status') }}s
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome</div>

                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        Hi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
