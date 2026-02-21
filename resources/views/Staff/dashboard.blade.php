@extends('layouts.app')

@section('content')
    <h1>Staff Dashboard</h1>

    <div class="cards">

        <div class="card blue">
            <h3>Total Products</h3>
            <p>{{ $totalProducts }}</p>
        </div>

        <div class="card orange">
            <h3>Today Sales</h3>
            <p>${{ number_format($todaySales, 2) }}</p>
        </div>

    </div>
@endsection
