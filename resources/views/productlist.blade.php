
@extends('main')

@section('title', 'Product List')

@section('navLinks')
    
    @include('pagedef.loggedOut')

@endsection

@section('mainContent')
<!DOCTYPE html>

<html>
    <h1>Products</h1>
<head>
    <title>Product List</title>
</head>

<body>
    This is list of products.

    @if ($user->type == 'vendor')

        @foreach ($productlist as $products)
            <tr>
                while($user->id == $productsell->vendor_id){
                    <td> {{ $products->id}} </td>
                        <td> {{ $products->name}} </td>
                        <td> {{ $products->type }} </td>
                        <td> {{ $products->value }} </td>
            </tr>
        @endforeach
                }
    @else
  
    @foreach ($productlist as $products)
            <tr>
                <td> {{ $products->name}} </td>
                <td> {{ $products->type }} </td>
                <td> {{ $products->value }} </td>
            </tr>

    @endforeach


</body>
</html>
@endsection
