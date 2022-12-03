
<tr id="row{{ $sum }}">
    <td> <input autocomplete="off" onchange="fieldChange(this.id)" list="featureList" class="form-control" id="ftr{{ $num }}" name="ftr{{ $num }}"> </td>
    <td> 
        <select class="form-control" id="ftropr{{ $num }}" name="ftropr{{ $num }}">
            <option value="eq">Equal To</option>
            <option value="ne">Not Equal To</option>
        </select>
    </td>
    <td> <input autocomplete="off" list="ftrval{{ $num }}list" required disabled class="form-control" id="ftrval{{ $num }}" name="ftrval{{ $num }}"> </td>
    <td> <button type="button" class="btn btn-light" onclick="promoteField()"> ^ </button> </td>
</tr>

<input type="hidden" value="ftr{{ $num }}" id="fld{{ $sum }}" name="fld{{ $sum }}">
<datalist id="ftrval{{ $num }}list"> </datalist>

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





