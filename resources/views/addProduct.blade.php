@extends('main')

@section('title', 'Dashboard')

@section('mainContent')
    <div id="adderFeedback"> </div>
    
    <script>
        var numAttributes = 1;
        var numFeatures = 0;
    </script>

    <form id="adder" onsubmit="return addProduct()">
        <h3>Basics:</h3>
        <table>
            <tr>
                <td> <label for="nameInput" >Product Name:</label> </td>
                <td> <label for="typeInput" >Product Category:</label> </td>
                @if ($user->type == 'vendor')
                    <td> <label for="priceInput" >Price ($):</label> </td>
                @endif
            </tr>
            <tr>
                <td> <input required type="text" id="nameInput" name="pname" class="form-control" placeholder="Folding Card Table"> </td>
                <td> <input required type="text" id="typeInput" name="ptype" class="form-control" placeholder="Furniture"> </td>
                @if ($user->type == 'vendor')
                    <td> <input required type="number" id="priceInput" name="sprice" min="0" step="0.01" class="form-control" placeholder="37.50"> </td>
                @endif
            </tr>
        </table>

        <h3>Details:</h3>
        <table>
            <thead>
                <td>Feature:</td>
                <td>Value:</td>
            </thead>
            <tbody id="recursiveArea">
                @include('form.attribute', ['num' => 0])
            </tbody>
        </table>
        <button class="btn btn-primary" type="submit">Add Product</button>
    </form>

    <script>
        function addProduct() {
            var toSend = $("#adder").serialize() + "&numFtr=" + numFeatures + "&numAtr=" + numAttributes;
            $("#adderFeedback").load("./ajax/addProduct", toSend);

            numAttributes = 0;
            numFeatures = 0;
            $.get("./ajax/form/atr", "num=" + numAttributes, function(data) {
                document.getElementById("recursiveArea").innerHTML = data;
                numAttributes++;
            });
            document.getElementById("adder").reset();
            return false;
        }

        function addFeature() {
            var toSend = "num=" + numFeatures;
            $.get("./ajax/form/ftr", toSend, function(data) {
                document.getElementById("recursiveBottom").outerHTML = data;
                numFeatures++;
            });
        }

        function addAttribute() {
            var toSend = "num=" + numAttributes;
            $.get("./ajax/form/atr", toSend, function(data) {
                document.getElementById("recursiveBottom").outerHTML = data;
                numAttributes++;
            });
        }

        function fieldChange(id) {
            if(document.getElementById(id).value != "") {
                var type = id.slice(0, 3);
                var num = id.slice(3);
                document.getElementById(type + 'val' + num).disabled = false;
            }
        }
    </script>

@endsection
