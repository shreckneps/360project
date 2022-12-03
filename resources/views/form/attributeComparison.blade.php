
<tr id="row{{ $sum }}">
    <td> <input autocomplete="off" onchange="fieldChange(this.id)" list="attributeList" class="form-control" id="atr{{ $num }}" name="atr{{ $num }}"> </td>
    <td> 
        <select class="form-control" id="atropr{{ $num }}" name="atropr{{ $num }}">
            <option value="eq">Equal To</option>
            <option value="lt">Less Than</option>
            <option value="gt">Greater Than</option>
        </select>
    </td>
    <td> <input autocomplete="off" list="atrval{{ $num }}list" disabled required type="number" step="any" class="form-control" id="atrval{{ $num }}" name="atrval{{ $num }}"> </td>
    <td> <button type="button" class="btn btn-light" onclick="promoteField()"> ^ </button> </td>
</tr>

<input type="hidden" value="atr{{ $num }}" id="fld{{ $sum }}" name="fld{{ $sum }}">
<datalist id="atrval{{ $num }}list"> </datalist>

<tr id="recursiveBottom">
    <td> <button type="button" value="ftr" class="btn btn-secondary" onclick="addComparison(this.value)">
        Add Qualitative Field
    </button> </td>
    <td> </td>
    <td> <button type="button" value="atr" class="btn btn-secondary" onclick="addComparison(this.value)">
        Add Quantitative Field
    </button> </td>
    <td> </td>
</tr>




