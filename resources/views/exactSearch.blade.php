@extends('main')

@section('title', 'Exact Search')

@section('mainContent')

<script>
    var numFields = 1;
</script>

<form id="query" onsubmit="return getQuery()">
    <input type="hidden" id="formType" name="formType" value="exact">
    <table class="table"> <tr>
        <td> <label for="typeInput">Product Category:</label> </td>
        <td> <label for="nameInput">Product Name:</label> </td>
    </tr>
    <tr>
        <td> <input list="typeList" required type="text" onchange="updateFieldHints(this.value, false)"
                    autocomplete="off" id="typeInput" name="ptype" class="form-control"> </td>
        <td> <input list="nameList" type="text" 
                    autocomplete="off" id="nameInput" name="pname" class="form-control"> </td>
    </tr> </table>

    <datalist id="typeList"> </datalist> <script> $("#typeList").load("./ajax/typeList"); </script>

    <table class="table">
        <thead>
            <td>Feature:</td>
            <td>Value:</td>
        </thead>
        <tbody id="recursiveArea">
            @include('form.generic', ['num' => 0])
        </tbody>
    </table>
    <button class="btn btn-primary" type="submit">Search for Products</button>
</form>

@include('form.supportFunctions')

<script>
    function getQuery() {
        useQuery = $("#query").serialize() + "&numFld=" + numFields;
        useRoute = "./ajax/exactSearch";
        $("#main-content").load(useRoute, useQuery);
        document.title = "Search Results";
        return false;
    }
</script>

@endsection
