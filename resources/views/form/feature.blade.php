
<tr>
    <td> <input autocomplete="off" onchange="fieldChange(this.id)" list="featureList" class="form-control field" id="ftr{{ $num }}" name="ftr{{ $num }}"> </td>
    <td> <input autocomplete="off" list="ftrval{{ $num }}list" disabled class="form-control" id="ftrval{{ $num }}" name="ftrval{{ $num }}"> </td>
</tr>

<datalist id="ftrval{{ $num }}list"> </datalist>

<tr id="recursiveBottom">
    <td> <button type="button" class="btn btn-secondary" onclick="addFeature()">
        Add Descriptive Field
    </button> </td>
    <td> <button type="button" class="btn btn-secondary" onclick="addAttribute()">
        Add Numeric Field
    </button> </td>
</tr>




