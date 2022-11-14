
@extends('main')

@section('title', 'Product List')

@section('mainContent')

All products listed.
<br>

        @foreach ($productlist as $products)
        <table>
        <tr>
    

                        <td> {{ $products->id}} </td>
                        <td> {{ $products->name}} </td>
                        <td> {{ $products->type }} </td>
                       
                        <br>
            </tr>
        @endforeach
</table>
@endsection
