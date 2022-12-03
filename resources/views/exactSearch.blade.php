@extends('main')

@section('title', 'Search Products')

@section('mainContent')
    <script>
        var numAttributes = 1;
        var numFeatures = 0;
    </script>

    <form id="query" onsubmit="return getQuery()">
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
                @include('form.attribute', ['num' => 0])
            </tbody>
        </table>
        <button class="btn btn-primary" type="submit">Search for Products</button>
    </form>

    @include('form.supportFunctions')
    
    <script>
        function getQuery() {
            var toSend = $("#query").serialize() + "&numFtr=" + numFeatures + "&numAtr=" + numAttributes;
            $("#main-content").load("./ajax/exactSearch", toSend);
            document.title = "Search Results";
            return false;
        }
    </script>


@endsection

