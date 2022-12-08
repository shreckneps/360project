@extends('main')

@section('title', 'Your Listings')

@section('mainContent')

<script> 
    useQuery="";
    useRoute="./ajax/userProducts";
    $("#main-content").load(useRoute, useQuery);
</script>

@endsection
