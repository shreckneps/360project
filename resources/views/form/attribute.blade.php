
<tr>
    <td> <input onchange="fieldChange(this.id)" list="attributeList" class="form-control" id="atr{{ $num }}" name="atr{{ $num }}"> </td>
    <td> <input disabled required type="number" step="any" class="form-control" id="atrval{{ $num }}" name="atrval{{ $num }}"> </td>
</tr>

<tr id="recursiveBottom">
    <td> <button type="button" class="btn btn-secondary" onclick="addFeature()">
        Add Qualitative Field
    </button> </td>
    <td> <button type="button" class="btn btn-secondary" onclick="addAttribute()">
        Add Quantitative Field
    </button> </td>
</tr>




