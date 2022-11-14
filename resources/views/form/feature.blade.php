
<tr>
    <td> <input onchange="fieldChange(this.id)" list="featureList" class="form-control" id="ftr{{ $num }}" name="ftr{{ $num }}"> </td>
    <td> <input required disabled list="valuesList" class="form-control" id="ftrval{{ $num }}" name="ftrval{{ $num }}"> </td>
</tr>

<tr id="recursiveBottom">
    <td> <button type="button" class="btn btn-secondary" onclick="addFeature()">
        Add Qualitative Field
    </button> </td>
    <td> <button type="button" class="btn btn-secondary" onclick="addAttribute()">
        Add Quantitative Field
    </button> </td>
</tr>




