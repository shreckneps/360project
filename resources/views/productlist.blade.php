<!DOCTYPE html>

<html>
    <h1>Products</h1>
<head>
    <title>Product List</title>
</head>

<body>
    This is list of products.
    @foreach ($productlist as $products)
    <li>{{$products->name, $products->type}} </li>
    @endforeach
</body>
</html>
