@extends('front.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12" style="display: none;" id="success-message">
                <div class="alert alert-success">Product added to cart!</div>
            </div>
            @if(count($products) > 0)
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $product->image }}" class="card-img-top img-fluid mx-auto d-block" alt="Product Image" style="width: 150px; height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->category }}</p>
                        <p class="card-text">Price: ${{ $product->price }}</p>
                        <button class="btn btn-primary" onclick="addToCart({{ $product->id }})">Add to Cart</button>
                    </div>
                </div>
            </div>

            @endforeach
            @else
            <div class="col-md-12">
                <h4>No result Found</h4>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">Total Bill</h4><hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>SubTotal Price</th>
                        </tr>
                    </thead>
                    <tbody id="table_body">
                        <tr>

                        </tr>
                    </tbody>
                </table>
                <hr>
                <h4 class="text-center">Grand Total <span>$0</span></h4>
                <button id="confirmButton" class="btn btn-primary btn-sm mt-3">Confirm Order</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var cartItems = {};
    var selectedProducts = {};

    function addToCart(productId) {
        $.ajax({
            url: '{{ url("/get-product") }}' + '/' + productId,
            type: 'GET',
            data: { product_id: productId },
            success: function (product) {
                if (cartItems[productId]) {
                    cartItems[productId].qty += 1;
                    cartItems[productId].totalPrice += product.price;
                    updateCartItemRow(productId);
                } else {
                    cartItems[productId] = {
                        product: product,
                        qty: 1,
                        totalPrice: product.price,
                    };
                    appendCartItemRow(productId);
                }
                if (selectedProducts[productId]) {
                    selectedProducts[productId].qty += 1;
                } else {
                    selectedProducts[productId] = {
                        id: productId,
                        name: product.name,
                        qty: 1,
                        price: product.price,
                        totalPrice: product.price,
                    };
                }
                updateTotalBill(product.price);
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

    function appendCartItemRow(productId) {
        var product = cartItems[productId].product;
        var newRow = '<tr data-product-id="' + productId + '">' +
            '<td><img src="' + product.image + '" alt="Product Image" style="width: 50px; height: 50px;"></td>' +
            '<td>' + product.name + '</td>' +
            '<td>' + cartItems[productId].qty + '</td>' +
            '<td>$' + product.price + '</td>' +
            '<td>$' + cartItems[productId].totalPrice + '</td>' +
            '</tr>';
        $('#table_body').append(newRow);

    }

    function updateCartItemRow(productId) {
        var product = cartItems[productId].product;
        var row = $('#table_body tr[data-product-id="' + productId + '"]');
        row.find('td:eq(2)').text(cartItems[productId].qty);
        row.find('td:eq(4)').text('$' + cartItems[productId].totalPrice.toFixed(2));
    }

    function updateTotalBill(productPrice) {
        var currentTotal = parseFloat($('h4.text-center span').text().replace('$', ''));
        var newTotal = currentTotal + productPrice;
        $('h4.text-center span').text('$' + newTotal.toFixed(2));
        $('#success-message').fadeIn();
                setTimeout(function() {
                    $('#success-message').fadeOut();
                }, 3000);
    }
    function clearTable() {
        $('#table_body').html('');
        $('h4.text-center span').text('$0');
    }

    $(document).ready(function () {
        $("#confirmButton").on("click", function () {
            var grandTotal = parseFloat($('h4.text-center span').text().replace('$', ''));
            if (grandTotal > 0) {
                var confirmed = confirm("Are you sure you want to confirm this order?");
                if (confirmed) {
                    var requestData = {
                        grand_total: grandTotal
                    };

                    var selectedProductsArray = [];

                    var index = 0;
                    for (var productId in selectedProducts) {
                        if (selectedProducts.hasOwnProperty(productId)) {
                            requestData['product_id[' + index + ']'] = productId;
                            requestData['qty[' + index + ']'] = selectedProducts[productId].qty;

                            selectedProductsArray.push({
                                id: productId,
                                qty: selectedProducts[productId].qty
                            });

                            index++;
                        }
                    }

                    requestData.selected_products = selectedProductsArray;
                    console.log(requestData);

                    $.ajax({
                        url: '{{ url("api/confirm-order") }}',
                        type: 'POST',
                        data: requestData,
                        success: function (response) {
                            console.log(response);
                            alert("Order confirmed!");
                            window.location.reload();
                        },
                        error: function (error) {
                            console.error(error);
                            alert("Failed to confirm the order.");
                        }
                    });
                } else {
                    alert("Order canceled.");
                }
            } else {
                alert("Your cart is empty.");
            }
        });
    });
</script>


@endsection
