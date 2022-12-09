@if (isset($paginated))
    <input hidden id="maxPage" value="{{ $pages }}">
    <table class="table"> <tr>
        <td> </td>
        <td> Page {{ $page }} out of {{ $pages }} </td>
        <td> </td>
    </tr> <tr>
        <td> <button value="{{ $page - 1}}" class="btn btn-primary" onclick="getPage(this.value)"
            type="button" @if ($page == 1) disabled @endif > Previous </button> </td>
        <td> <input type="number" id="jumpDest" value="{{ $page }}"
                    min="1" max="{{ $pages }}" onchange="boundsCheck(this.id)">
        <button type="button" class="btn btn-primary" 
            onclick="getPage($('#jumpDest').val())">Go </button> </td>
        <td> <button value="{{ $page + 1}}" class="btn btn-primary" onclick="getPage(this.value)"
            type="button" @if ($page == $pages) disabled @endif > Next </button> </td>
    </tr> </table>
@endif

@if ($user->type == 'customer' && isset($products->first()->cancellation_fee))
    <div hidden id="offerFeedback"> </div>
@endif

<table class="table">
    <tr>
        <th scope="col">Category</th>
        <th scope="col">Name</th>
        @if (isset($products->first()->price))
            <th scope="col">Price</th>
        @endif
        <th scope="col">Details</th>
        @if (isset($deletes))
            <th scope="col">Delete Listing</th>
        @endif
        @if (isset($adds))
            <th scope="col">List This Product</th>
        @endif
    </tr>
    @foreach ($products as $key => $product)
        @php
            if(isset($product->product_id)) {
                $pid = $product->product_id;
            } else {
                $pid = $product->id;
            }
        @endphp

        <tr id="row{{ $pid }}">
            <td>{{ $product->type }}</td>
            <td>{{ $product->name }}</td>
            @if (isset($product->price))
                <td>{{ $product->price }}</td>
            @endif
            <td> 
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal"
                 data-name="{{ $product->name }}" data-type="{{ $product->type }}" data-key="{{ $pid }}"
                 @if (isset($product->price)) data-price="{{ $product->price }}" @endif
                 @if (isset($product->vendor_id)) data-vid="{{ $product->vendor_id }}" @endif
                 @if (isset($product->cancellation_fee)) data-cancellation_fee="{{ $product->cancellation_fee }}" @endif > View </button> 
            </td>
            @if (isset($deletes))
                <td><button type="button" class="btn btn-danger" value="{{ $pid }}" onclick="deleteProduct(this.value)">X</button></td>
            @endif
            @if (isset($adds))
                <td><button type="button" class="btn btn-secondary" value="{{ $pid }}" onclick="addExistingProduct(this.value)">O</button></td>
            @endif

        </tr>
    @endforeach
</table>

<div class="modal fade" id="detailModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Listing Detail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
            </div>
            <div class="modal-body"> 
                <table class="table">
                    <thead> <tr> 
                        <td>Category:</td>
                        <td id="detailType">TYPE</td>
                    </tr>
                    @if (isset($products->first()->price)) <tr>
                        <td>Price:</td>
                        <td id="detailPrice">PRICE</td>
                    </tr> @endif 
                    @if (isset($products->first()->cancellation_fee)) <tr>
                        <td>Cancellation Fee:</td>
                        <td id="detailFee">FEE</td>
                    </tr> @endif </thead>
                    <tbody id="detailList">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">

                @if ($user->type == 'customer' && isset($products->first()->cancellation_fee))
                <input type="hidden" id="productOffer">
                <input type="hidden" id="vendorOffer">
                <input type="hidden" id="feeOffer">
                <input type="number" step="0.01" min="0" id="priceOffer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        onclick="makeOffer()">Make Offer</button>
                @endif

                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Back to List</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#detailModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find(".modal-title").text(button.data("name"));
        modal.find("#detailType").text(button.data("type"));
        @if (isset($products->first()->price))
            modal.find("#detailPrice").text(button.data("price"));
        @endif

        @if (isset($products->first()->cancellation_fee))
            modal.find("#detailFee").text(button.data("cancellation_fee"));
        @endif
        
        @if ($user->type == 'customer' && isset($products->first()->cancellation_fee))
            modal.find("#priceOffer").val(parseFloat(button.data("price")));
            modal.find("#productOffer").val(button.data("key"));
            modal.find("#vendorOffer").val(button.data("vid"));
            modal.find("#feeOffer").val(button.data("cancellation_fee"));

        @endif

        $("#detailList").load("./ajax/detailList", "product_id=" + button.data("key"));
    
        //console.log(button.data("name"));
        //console.log(button.data("type"));
        @if (isset($products->first()->price))
            //console.log(button.data("price"));
        @endif
    })

    @if (isset($deletes))
        function deleteProduct(id) {
            //console.log(parseInt(id) + 1);
            $.get("./ajax/deleteProduct", "del_id=" + id);
            document.getElementById("row" + id).outerHTML = "";
        }
    @endif

    @if ($user->type == 'customer' && isset($products->first()->cancellation_fee))
        function makeOffer() {
            var toSend = "offerPrice=" + $("#priceOffer").val() + "&product=" + $("#productOffer").val();
            toSend += "&vendor=" + $("#vendorOffer").val() + "&fee=" + $("#feeOffer").val();
            $("#offerFeedback").load("./ajax/addOffer", toSend, function(data) {
                showAlert($("#offerFeedback").html());
            });
        }
    @endif
</script>
