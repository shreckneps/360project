
@php
    $pages = array(
        url('/') => 'Dashboard',
        url('/login') => 'Log In',
        url('/register') => 'Register'
    );
@endphp


@foreach ($pages as $url => $title)
    @if ($url == url()->current())
        <a class="nav-link active" href="{{ $url }}">{{ $title }}</a>
    @else
        <a class="nav-link" href="{{ $url }}">{{ $title }}</a>
    @endif
@endforeach

