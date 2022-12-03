Total value needed is: {{ $sum }}

@foreach ($lists as $key => $list)
    <h4>{{ $key }}</h4>
    @include('list', ['products' => $list->sortBy('price')])

@endforeach
