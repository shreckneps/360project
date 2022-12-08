@extends('main')

@section('title', 'Data Generation')

@section('mainContent')

<div hidden id="adderFeedback"> </div>

<form id="bookgen" onsubmit="return addBooks()">
    <table> <tr>
        <td> <input required type="number" id="authorAmount" name="authorAmount" class="form-control"> </td>
        <td> <button type="submit" class="btn btn-primary">Add Authors</button> </td>
    </tr> </table>
</form>

<form id="homegen" onsubmit="return addHomes()">
    <table> <tr>
        <td> <input required type="number" id="cityAmount" name="cityAmount" class="form-control"> </td>
        <td> <button type="submit" class="btn btn-primary">Add Cities</button> </td>
    </tr> </table>
</form>

<script>

    function addBooks() {
        var toSend = $("#bookgen").serialize();
        $("#adderFeedback").load("./datagen/author", toSend, function(data) {
            showAlert($("#adderFeedback").html());
        });
        return false;
    }

    function addHomes() {
        var toSend = $("#homegen").serialize();
        $("#adderFeedback").load("./datagen/home", toSend, function(data) {
            showAlert($("#adderFeedback").html());
        });
        return false;
    }

</script>

@endsection
