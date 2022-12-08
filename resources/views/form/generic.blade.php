
<tr>
    <td> <input autocomplete="off" onchange="genericFieldChange(this.id)" list="fieldList" class="form-control field" id="fld{{ $num }}" name="fld{{ $num }}"> </td>
    <td> <input autocomplete="off" list="fld{{ $num }}list" disabled step="any" class="form-control" id="fld{{ $num }}val" name="fld{{ $num }}val"> </td>
</tr>

<input type="hidden" value="" id="fld{{ $num }}type" name="fld{{ $num }}type">
<datalist id="fld{{ $num }}list"> </datalist>

<tr id="recursiveBottom">
    <td> <button type="button" class="btn btn-secondary" onclick="addGeneric()">
        Add Field
    </button> </td>
    <td> </td>
</tr>
