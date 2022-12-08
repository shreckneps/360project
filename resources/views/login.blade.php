@extends('main')

@section('title', 'Login')

@section('navLinks')
    
    @include('pagedef.loggedOut')

@endsection

@section('mainContent')
    This is the login form for existing users. 
    New users need to first <a href="register">Register</a> their account.
    <form method="post">
    @csrf
        <table>
        <tr> <td> <label for="username">Username: </label> </td> 
        <td> <input class="form-control" required type="text" id="username" name="username"> </td> </tr>
        <tr> <td> <label for="password">Password: </label> </td> 
        <td> <input class="form-control" required type="password" id="password" name="password"> </td> </tr>
        <tr> <td> </td> <td align="right"> <button class="form-control btn btn-primary" name="login" type="submit">Login</button> </td> </tr>
        </table>
    </form>
    
    @if (isset($failure))
        <script> showAlert("{{ $failure }}", "danger") </script>
    @endif

@endsection

