<tr>
    <td> <input autocomplete="off" class="form-control" id="{{ $side . $num }}type" type="text" name="{{ $side . $num }}type"> </td>
    <td> <input autocomplete="off" class="form-control" id="{{ $side . $num }}name" type="text" name="{{ $side . $num }}name"> </td>
</tr>

<tr id="recursiveBottom-{{ $side }}">
    <td> </td>
    <td> <button type="button" class="btn btn-secondary" 
                 value="{{ $side }}" onclick="addField(this.value)">
        Add Field </button> </td>
</tr>
