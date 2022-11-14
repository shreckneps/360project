@extends('main')

@section('title', 'Dynamic Testing')

@section('mainContent')
    <script>
        var num = 1;
    </script>
    <div id="serializeArea"> </div>

    <form id="recursiveEntry" onsubmit="return serialize()">
        <datalist id="sampleList">
            <option value="eins">
            <option value="zwei">
            <option value="drei">
            <option value="vier">
            <option value="funf">
        </datalist>
        <span id="recursiveArea">
            @include('dynamicRecursive', ['num' => 0])
        </span>
        <button id="serializeButton" type="submit" class="btn btn-primary"> Serialize </button>
    </form>

    <script>
        function recursiveExpansion() {
            $.get("./dynamic", "num=" + num, function(data) {
                document.getElementById("recursiveBottom").outerHTML = data;
                num++;
            });
        }
        function serialize() {
            $("#serializeArea").load("./ajax/serialize", $("#recursiveEntry").serialize() + "&num=" + num);
            num = 0;
            $.get("./dynamic", "num=" + num, function(data) {
                document.getElementById("recursiveArea").innerHTML = data;
                num++;
            });
            document.getElementById("recursiveEntry").reset();
            return false;
        }
    </script>
@endsection
