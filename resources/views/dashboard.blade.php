@extends('main')

@section('title', 'Dashboard')

@section('mainContent')

    Username: {{ $user->username }} <br>
    @if ($user->type == 'vendor')
        You are a vendor. In the future, you'll be able to list the products and services you offer from this page.
    @else
        You are a client. In the future, you'll be able to list your needs for products and services from this page.
    @endif

    <form method="post">
        @csrf
        <button class="btn" name="logout" type="submit">Logout</button>
        <button class="btn" name="productlist" type="submit">List Products</button>
    </form>

@endsection
