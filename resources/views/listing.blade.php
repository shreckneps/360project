<table>
    @foreach ($arr as $key => $val)
    <tr>
        <td> {{ $key }} </td>
        <td> {{ $val }} </td>
    </tr>
    @endforeach
</table>
