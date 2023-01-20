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
            <div class="card-header">Welcome</div>

            <div class="card-body">
                <div class="alert alert-success" role="alert">
                    Hi
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
