@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>

    <div class="cards">

        <div class="card blue">
            <h3>Total Products</h3>
            <p>{{ $totalProducts }}</p>
        </div>

        <div class="card green">
            <h3>Total Categories</h3>
            <p>{{ $totalCategories }}</p>
        </div>

        <div class="card orange">
            <h3>Today Sales</h3>
            <p>${{ number_format($todaySales, 2) }}</p>
        </div>

        <div class="card red">
            <h3>Low Stock</h3>
            <p>{{ $lowStock }}</p>
        </div>

    </div>
@endsection
