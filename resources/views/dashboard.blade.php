@extends('main')

@section('title', 'Dashboard')

@section('mainContent')

    Username: {{ $user->username }} <br>
    @if ($user->type == 'vendor')
        You are a vendor. You can review your products for sale, or list more products for sale.
    @else
        You are a customer. You can review your owned products, add more products to your owned list, or search for products being sold via two different methods.
    @endif

    <form method="post">
        @csrf
        <button class="btn btn-primary" name="logout" type="submit">Logout</button>
    
    </form>

@endsection
