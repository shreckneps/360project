@extends('main')

@section('title', 'Your Offers')

@section('mainContent')

<div hidden id="offerFeedback"> </div>

<table class="table">
    <tr>
        <th scope="col">Offer Status</th>
        <th scope="col">Product Name</th>
        <th scope="col">Offer Payment</th>
        <th scope="col">Details</th>
    </tr>

    @foreach ($offers as $key => $offer)
        <tr id="row{{ $offer->id }}">
            <td>{{ $offer->status }}</td>
            <td>{{ $offer->name }}</td>
            <td>{{ $offer->price }}</td>
            <td> <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                         data-bs-target="#detailModal" data-name="{{ $offer->name }}"
                         data-price="{{ $offer->price }}" data-fee="{{ $offer->cancellation_fee }}"
                         data-self="{{ $offer[$self] }}" data-other="{{ $offer[$other] }}"
                         data-key="{{ $offer->id }}" data-product="{{ $offer->product_id }}"
                 >View </button> </td>
        </tr>
    @endforeach
</table>

<div class="modal fade" id="detailModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Offer Detail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
            </div>
            <div class="modal-body">
                <div hidden id="otherName"> </div>
                <p id="detailStatus">DETAIL PARAGRAPH!</p>
                <p id="secondStatus">DETAIL PARAGRAPH!</p>
                <h5>Offer Details:</h5>
                <table class="table">
                    <tr>
                        <td>Payment:</td>
                        <td id="detailPayment">PAYMENT</td>
                    </tr>
                    <tr>
                        <td>Cancellation Fee:</td>
                        <td id="detailFee">FEE</td>
                    </tr>
                </table>
                
                <h5>Product Details:</h5>
                <table class="table">
                    <tbody id="detailList">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div id="otherWaiting">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                    >Return to List</button>
                </div>

                <div id="selfWaiting">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                     id="rejectButton" onclick="rejectOffer(this.value)";
                    >Reject Offer</button>

                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                     id="acceptButton" onclick="acceptOffer(this.value)";
                    >Accept Offer</button>
                </div>

                <div id="otherRejected">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                     id="dismissRejected" onclick="rejectOffer(this.value)";
                    >Dismiss Offer</button>
                </div>

                <div id="bothAccepted">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                     id="dismissAccepted" onclick="dismissOffer(this.value)";
                    >Finalize Offer</button>

                    @if ($user->type == 'customer')
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                         id="listAccepted" onclick="dismissOffer(this.value, true)";
                        >Finalize Offer and List Product</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#detailModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find(".modal-title").text("Offer for: " + button.data("name"));
        modal.find("#detailPayment").text(button.data("price"));
        modal.find("#detailFee").text(button.data("fee"));
        modal.find("#secondStatus").attr("hidden", "hidden");
        modal.find("#otherWaiting").attr("hidden", "hidden");
        modal.find("#selfWaiting").attr("hidden", "hidden");
        modal.find("#otherRejected").attr("hidden", "hidden");
        modal.find("#bothAccepted").attr("hidden", "hidden");

        var other = button.data("other");
        var yours = button.data("self");
        var statusText;
        if(other == "waiting") {
            statusText = "You have made this offer. The other party must now accept or reject it.";
            modal.find("#otherWaiting").attr("hidden", null);
        } else if(yours == "waiting") {
            statusText = "Another party has made an offer.";
            statusText += " They are waiting for you to accept or reject it.";

            modal.find("#selfWaiting").attr("hidden", null);
            modal.find("#rejectButton").val(button.data("key"));
            modal.find("#acceptButton").val(button.data("key"));

        } else if(other == "rejected") {
            statusText = "The other party rejected your offer.";
            statusText += " Use the button below to remove it from your list of offers.";

            modal.find("#otherRejected").attr("hidden", null);
            modal.find("#dismissRejected").val(button.data("key"));

        } else if(other == "accepted" || other == "finalized") {
            statusText = "Both parties have accepted the offer.";

            modal.find("#bothAccepted").attr("hidden", null);
            modal.find("#dismissAccepted").val(button.data("key"));
        
            var toSend = "offer=" + button.data("key");
            modal.find("#otherName").load("./ajax/offerOtherName", toSend, function(data) {
                @if ($user->type == 'customer') 
                    var secondText = "This product is being sold by <b>";
                    secondText += modal.find("#otherName").text() + ". </b>";
                    secondText += " Please promptly send them the agreed-on payment.";
                @else
                    var secondText = "This product is being sold to <b>";
                    secondText += modal.find("#otherName").text() + ". </b>";
                    secondText += " Please promptly send them the product.";
                @endif
                secondText += " Failure to do so will result in the cancellation fee being levied.";
                modal.find("#secondStatus").html(secondText);
                modal.find("#secondStatus").attr("hidden", null);
            });

            @if ($user->type == 'customer')
                statusText += " Use the buttons below to finalize the offer, optionally adding the";
                statusText += " product to your list of owned products.";

                modal.find("#listAccepted").val(button.data("key"));
            @else
                statusText += " Use the button below to finalize the offer.";
            @endif
            
        }

        modal.find("#detailStatus").text(statusText);

        $("#detailList").load("./ajax/detailList", "product_id=" + button.data("product"));
    })

    function rejectOffer(id) {
        document.getElementById("row" + id).outerHTML = "";
        var toSend = "offer=" + id;
        $("#offerFeedback").load("./ajax/rejectOffer", toSend, function(data) {
            //showAlert($("#offerFeedback").html());
        });
    }

    function acceptOffer(id) {
        var toSend = "offer=" + id;
        $("#offerFeedback").load("./ajax/acceptOffer", toSend, function(data) {
            showAlert($("#offerFeedback").html());
        });
    }

    function dismissOffer(id, list = false) {
        document.getElementById("row" + id).outerHTML = "";
        var toSend = "offer=" + id;
        if(list) {
            toSend += "&add=true";
        }
        $("#offerFeedback").load("./ajax/dismissOffer", toSend, function(data) {
            if(list) { showAlert($("#offerFeedback").html()); }
        });
    }


</script>

@endsection
