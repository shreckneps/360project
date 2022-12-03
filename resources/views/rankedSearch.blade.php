@extends('main')

@section('title', 'Ranked Search')

@section('mainContent')
    <script>
        var numAttributes = 1;
        var numFeatures = 0;
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
                <td> </td>
            </thead>
            <tbody id="recursiveArea">
                @include('form.attributeComparison', ['num' => 0, 'sum' => 0])
            </tbody>
        </table>
        <button class="btn btn-primary" type="submit">Search for Products</button>
    </form>

    @include('form.supportFunctions')
    
    <script>
        function getQuery() {
            var toSend = $("#query").serialize() + "&numFtr=" + numFeatures + "&numAtr=" + numAttributes;
            $("#main-content").load("./ajax/rankedSearch", toSend);
            document.title = "Search Results";
            return false;
        }
    </script>

@endsection
