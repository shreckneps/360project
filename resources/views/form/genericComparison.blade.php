
<tr>
    <td> <input autocomplete="off" onchange="genericFieldChangeComparison(this.id)" list="fieldList" class="form-control field" id="fld{{ $num }}" name="fld{{ $num }}"> </td>
    <td> <select class="form-control" id="fld{{ $num }}opr" name="fld{{ $num }}opr"> <option value="ne">Not Equal To</option> </select> </td>
    <td> <input autocomplete="off" list="fld{{ $num }}list" disabled step="any" class="form-control" id="fld{{ $num }}val" name="fld{{ $num }}val"> </td>
    <td> <input required class="form-control" type="number" id="fld{{ $num }}weight" name="fld{{ $num }}weight" style="width: 5em" value="0"> </td>
</tr>

<input type="hidden" value="" id="fld{{ $num }}type" name="fld{{ $num }}type">
<datalist id="fld{{ $num }}list"> </datalist>

<tr id="recursiveBottom">
    <td> <button type="button" class="btn btn-secondary" onclick="addGenericComparison()">
        Add Field
    </button> </td>
    <td> </td>
    <td> </td>
    <td> </td>
</tr>
