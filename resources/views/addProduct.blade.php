@extends('main')

@section('title', 'Add Listing')

@section('mainContent')
    <div id="adderFeedback"> </div>
    
    <script>
        var numAttributes = 1;
        var numFeatures = 0;
    </script>

    <form id="adder" onsubmit="return addProduct()">
        <h3>Basics:</h3>
        <table class="table">
            <tr>
                <td> <label for="typeInput" >Product Category:</label> </td>
                <td> <label for="nameInput" >Product Name:</label> </td>
                @if ($user->type == 'vendor')
                    <td> <label for="priceInput" >Price ($):</label> </td>
                @endif
            </tr>
            <tr>
                <td> <input list="typeList" required type="text" onchange="updateFieldHints(this.value, false)"
                            autocomplete="off" id="typeInput" name="ptype" class="form-control" placeholder="Furniture"> </td>
                <td> <input list="nameList" required type="text" onchange="checkExisting()"
                            autocomplete="off" id="nameInput" name="pname" class="form-control" placeholder="Folding Card Table"> </td>
                @if ($user->type == 'vendor')
                    <td> <input required type="number" id="priceInput" name="sprice" min="0" step="0.01" class="form-control" placeholder="37.50"> </td>
                @endif
            </tr>
        </table>

        <datalist id="typeList"> </datalist> <script> $("#typeList").load("./ajax/typeList"); </script>

        <h3>Specify Details:</h3>
        <table class="table">
            <thead> <tr>
                <td>Feature:</td>
                <td>Value:</td>
            </tr> </thead>
            <tbody id="recursiveArea">
                @include('form.attribute', ['num' => 0])
            </tbody>
        </table>
        <button class="btn btn-primary" type="submit">Add Product</button>
    </form>

    <div id="existing"> </div>

    @include('form.supportFunctions')

    <script>

        function addExistingProduct(id) {
            var toSend = "product_id=" + id;
            @if ($user->type == 'vendor')
                var price = document.getElementById("priceInput").value;
                if(price == "") {
                    document.getElementById("adderFeedback").innerHTML = "Please specify a price for your listing of this product.";
                    return false;
                }
                toSend = toSend + "&sprice=" + price;
            @endif
            $("#adderFeedback").load("./ajax/addListing", toSend);

            numAttributes = 0;
            numFeatures = 0;
            $.get("./ajax/form/atr", "num=" + numAttributes, function(data) {
                document.getElementById("recursiveArea").innerHTML = data;
                numAttributes++;
            });

            document.getElementById("adder").reset();
            document.getElementById("existing").innerHTML = "";
        }

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
            document.getElementById("existing").innerHTML = "";
            $("#typeList").load("./ajax/typeList"); 
            return false;
        }

        function checkExisting() {
            var type = document.getElementById("typeInput").value;
            var name = document.getElementById("nameInput").value;
            if(type != "" && name != "") {
                var toSend = "type=" + type + "&name=" + name;
                $("#existing").load("./ajax/listExisting", toSend);
            }
        }

    </script>

@endsection
