
<tr>
    <td> <input autocomplete="off" onchange="fieldChange(this.id)" list="attributeList" class="form-control field" id="atr{{ $num }}" name="atr{{ $num }}"> </td>
    <td> <input autocomplete="off" list="atrval{{ $num }}list" disabled required type="number" step="any" class="form-control" id="atrval{{ $num }}" name="atrval{{ $num }}"> </td>
</tr>

<datalist id="atrval{{ $num }}list"> </datalist>

<tr id="recursiveBottom">
    <td> <button type="button" class="btn btn-secondary" onclick="addFeature()">
        Add Descriptive Field
    </button> </td>
    <td> <button type="button" class="btn btn-secondary" onclick="addAttribute()">
        Add Numeric Field
    </button> </td>
</tr>




