
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
                while($user->id == $products->vendor_id){
                        <td> {{ $products->id}} </td>
                        <td> {{ $products->name}} </td>
                        <td> {{ $products->type }} </td>
                        <td> {{ $products->value }} </td>
            </tr>
        @endforeach
                }
    @else

        @if($request->has('ownslist'))   

            @foreach($productlist as $products)
                @foreach($ownslist as $owns)
                @if ($user->id == $owns->customer_id)
                <tr>
                      while($products->id == $owns->product_id){
                        <td> {{ $owns->id}} </td>
                        <td> {{ $products->name}} </td>
                        <td> {{ $products->type }} </td>
                        
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
            </tr>

        @endforeach
        @endif
    @endif

</body>
</html>
@endsection
