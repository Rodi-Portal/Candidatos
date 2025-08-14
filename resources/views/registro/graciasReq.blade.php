@extends('layouts.app')

@section('content')
<div class="container my-5 text-center">
    @if(session('mensaje'))
        <h2 style="color: #0C9DD3;" class="mb-3">{{ session('mensaje') }}</h2>
    @endif
</div>
@endsection
