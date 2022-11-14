
@extends('main')

@section('title', 'Product List')

@section('navLinks')
    
    @include('pagedef.loggedOut')

@endsection

@section('mainContent')

this is a list. Why wont this print?


<table>

   OR this damnit. 

    @if ($user->type == 'vendor')

        @foreach ($productlist as $products)
            <tr>
                while($user->id == $products->vendor_id){
                        <td> {{ $products->id}} </td>
                        <td> {{ $products->name}} </td>
                        <td> {{ $products->type }} </td>
                        <td> {{ $products->value }} </td>
                        <br>
            </tr>
        @endforeach
                }
    @else
    <tr> 
            <td> <button name="ownslist" value="ownslist" type="submit">List Owned Products</button> </td> 
            <td> <button name="productlist" value="productlist" type="submit">List All Products</button> </td> 
        </tr>
        @if($request->has('ownslist'))   

            @foreach($productlist as $products)
                @foreach($ownslist as $owns)
                @if ($user->id == $owns->customer_id)
                <tr>
                      while($products->id == $owns->product_id){
                        <td> {{ $owns->id}} </td>
                        <td> {{ $products->name}} </td>
                        <td> {{ $products->type }} </td>
                        <br>
                    }
                </tr>
                    
                @endif
                
                @endforeach    
            @endforeach
  
        @else
        
        @foreach ($productlist as $products)
            <tr>
                <td> {{ $products->id}} </td>
                <td> {{ $products->name}} </td>
                <td> {{ $products->type }} </td>
                <td> {{ $products->value }} </td>
                <br>
            </tr>

        @endforeach
        @endif
    @endif
    @endsection

    </table>
