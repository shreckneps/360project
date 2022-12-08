@extends('main')

@section('title', 'Register')

@section('navLinks')
    
    @include('pagedef.loggedOut')

@endsection

@section('mainContent')

    This is the registration form for new users. 
    Existing users should instead <a href="login">Login</a> to their account.
    <form method="post">
        @csrf
        <table>
        <tr> <td> <label for="username">Username: </label> </td>
        <td> <input class="form-control" required type="text" id="username" name="username"> </td> </tr>
        <tr> <td> <label for="password">Password: </label> </td>
        <td> <input class="form-control" required type="password" id="password" name="password"> </td> </tr>
        <tr> <td> <label for="password_conf">Confirm Password: </label> </td>
        <td> <input class="form-control" required type="password" id="password_conf" name="password_conf"> </td> </tr>
        <tr> 
            <td> <button class="form-control btn btn-primary" name="register" value="vendor" type="submit">Register as Vendor</button> </td> 
            <td> <button class="form-control btn btn-primary" name="register" value="customer" type="submit">Register as Customer</button> </td> 
        </tr>
        </table>
    </form>

    @if (isset($failure))
        <script> showAlert("{{ $failure }}", "danger") </script>
    @endif

@endsection

