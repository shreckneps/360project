@extends('main')

@section('title', 'Ranked Search')

@section('mainContent')

<script>
    var numFields = 1;
</script>

<form id="query" onsubmit="return getQuery()">
    <table class="table"> <tr>
        <td> <label for="typeInput">Product Category:</label> </td>
    </tr>
    <tr>
        <td> <input list="typeList" required type="text" onchange="updateFieldHints(this.value, true)"
                    autocomplete="off" id="typeInput" name="ptype" class="form-control"> </td>
    </tr> </table>

    <datalist id="typeList"> </datalist> <script> $("#typeList").load("./ajax/typeList"); </script>

    <table class="table">
        <thead>
            <td>Feature:</td>
            <td>Should Be:</td>
            <td>Value:</td>
            <td>Weight:</td>
        </thead>
        <tbody id="recursiveArea">
            @include('form.genericComparison', ['num' => 0])
        </tbody>
    </table>
    <button class="btn btn-primary" type="submit">Search for Products</button>
</form>

@include('form.supportFunctions')

<script>
    function getQuery() {
        useQuery = $("#query").serialize() + "&numFld=" + numFields;
        useRoute = "./ajax/rankedSearch";
        //console.log("Submitting: " + toSend);
        //return false;
        $("#main-content").load(useRoute, useQuery);
        document.title = "Search Results";
        return false;
    }
</script>

@endsection
