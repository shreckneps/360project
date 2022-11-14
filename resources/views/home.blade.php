@extends('main')

@section('navLinks')
    
    @include('pagedef.loggedOut')

@endsection

@section('mainContent')

    You are not logged in. To access your dashboard, please
    <a href="login">Login</a>
    or
    <a href="register">Register.</a>

@endsection

