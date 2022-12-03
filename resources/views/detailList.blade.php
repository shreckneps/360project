@foreach ($details as $key => $detail)
    <tr>
        <td>{{ $detail->name }}:</td>
        <td>{{ $detail->value }}</td>
    </tr>
@endforeach
