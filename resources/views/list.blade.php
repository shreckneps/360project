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
                 @if (isset($product->price)) data-price="{{ $product->price }}" @endif > View </button> 
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
                    </tr> @endif </thead>
                    <tbody id="detailList">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
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

        $("#detailList").load("./ajax/detailList", "product_id=" + button.data("key"));
    
        console.log(button.data("name"));
        console.log(button.data("type"));
        @if (isset($products->first()->price))
            console.log(button.data("price"));
        @endif
    })

    @if (isset($deletes))
        function deleteProduct(id) {
            //console.log(parseInt(id) + 1);
            $.get("./ajax/deleteProduct", "del_id=" + id);
            document.getElementById("row" + id).outerHTML = "";
        }
    @endif
</script>
