{{ $options = $options->sort() }}

@foreach ($options as $key => $option)
    <option value="{{ $option }}">
@endforeach
