@extends('main')

@section('title', 'Add Needmap')

@section('mainContent')
    <div id="adderFeedback"> </div>

    <script>
        var numLhs = 1;
        var numRhs = 1;
    </script>

    <form id="adder" onsubmit="return addNeedmap()">
        <table class="table">
            <tr>
                <td> <label for="nameInput">Needmap Name:</label> </td>
                <td> <label for="descInput">Description:</label> </td>
            </tr>
            <tr>
                <td> <input required type="text" autocomplete="off" id="nameInput"
                            name="mname" class="form-control"> </td>
                <td> <input required type="text" autocomplete="off" id="descInput"
                            name="mdesc" class="form-control"> </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td valign="top"> <table class="table">
                    <thead> <tr>
                        <td>Product Type:</td>
                        <td>Attribute Name:</td>
                    </tr> </thead>
                    <tbody id="recursiveArea-lhs">
                        @include('form.need', ['side' => 'lhs', 'num' => 0])
                    </tbody>
                </table> </td>

                <td valign="top"> <table class="table">
                    <thead> <tr>
                        <td>Product Type:</td>
                        <td>Attribute Name:</td>
                    </tr> </thead>
                    <tbody id="recursiveArea-rhs">
                        @include('form.need', ['side' => 'rhs', 'num' => 0])
                    </tbody>
                </table> </td>
            </tr>
        </table>

        <button class="btn btn-primary" type="submit">Add Needmap</button>
    </form>
    
    <script>
        function addNeedmap() {
            var toSend = $("#adder").serialize() + "&numLhs=" + numLhs + "&numRhs=" + numRhs;

            $("#adderFeedback").load("./ajax/addNeedmap", toSend);

            numLhs = 1;
            numRhs = 1;
            $.get("./ajax/form/need", "side=lhs&num=0", function(data) {
                document.getElementById("recursiveArea-lhs").innerHTML = data;
            });
            $.get("./ajax/form/need", "side=rhs&num=0", function(data) {
                document.getElementById("recursiveArea-rhs").innerHTML = data;
            });

            document.getElementById("adder").reset();
            return false;
        }

        function addField(side) {
            if(side == "lhs") {
                var num = numLhs;
            } else {
                var num = numRhs;
            }
            var toSend = "side=" + side + "&num=" + num;
            $.get("./ajax/form/need", toSend, function(data) {
                document.getElementById("recursiveBottom-" + side).outerHTML = data;
                if(side == "lhs") {
                    numLhs++;
                } else {
                    numRhs++;
                }
            });
        }
    </script>

@endsection
