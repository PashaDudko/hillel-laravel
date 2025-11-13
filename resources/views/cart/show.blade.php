@extends('welcome1')

@section('content')
    YOUR CART VIEW
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cartItems as $item)
                <tr>
                    <td>
                        {{$loop->index + 1}}
                    </td>
                    <td>
                         {{$item['product_name']}}
                    </td>
                    <td>
                        <span class="product_price">{{$item['price']}}</span>
                    </td>
                    <td>
                        <form class="cart-update-form" method="POST" action="{{ route('cart.update') }} ">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$item['product_id']}}">
                            <input type="hidden" name="new_quantity" id="{{$item['slug']}}_new_quantity" value="">
                            <button type="button" class="btn border-2 btn-outline-dark" onclick="changeProductQuantityInCart('{{$item['slug']}}', 'plus', '{{$item['in_stock']}}', this);">+</button>
                            <span id="{{$item['slug']}}">{{$item['quantity']}}</span>
                            <button type="button" class="btn border-2 btn-outline-dark" onclick="changeProductQuantityInCart('{{$item['slug']}}', 'minus', '{{$item['in_stock']}}', this);">-</button>
                        </form>
                    </td>
                    <td>
                        <span class="total" id="{{$item['slug']}}_total">{{$item['price'] * $item['quantity']}}</span> $
                    </td>
                    <td>
                        <form method="POST" action="{{route('cart.product.remove', $item['product_id'])}}">
                            @csrf
                            @method('DELETE')
                            <button><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <th scope="row">Total Price: <span id="total_price">{{$totalPrice}}</span> $</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <form method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Clear Cart</button>
                </form>
            </th>
            </tbody>
        </table>
        <button class="btn btn-dark" onclick="window.location.href = '/order/create'">Create Order</button>
    </div>
@endsection

@push('scripts')
    <script>
        function changeProductQuantityInCart(product_name, sign, in_stock, buttonElement) {
            let current_quantity = Number(document.getElementById(product_name).innerHTML);
            let row = buttonElement.closest('tr');
            let product_price = Number(row.querySelector(".product_price").innerHTML);
            if (sign == 'plus') {
                current_quantity++;
                if (current_quantity > in_stock) {
                    current_quantity = in_stock;
                    alert('Product maximum quantity in stock is reached!');
                }
            } else {
                current_quantity--;
                if (current_quantity < 0) {
                    current_quantity = 0;
                }
            }

            document.getElementById(product_name).innerHTML = current_quantity.toString();
            document.getElementById(product_name + "_new_quantity").value = current_quantity.toString();
            document.getElementById(product_name + "_total").innerHTML = (current_quantity * product_price).toString();
            $(buttonElement).closest('.cart-update-form').submit();
        }

        $(document).ready(function() {
            $('.cart-update-form').submit(function(e) {
                e.preventDefault();
                let $form = $(this);
                const totalElements = document.getElementsByClassName('total');
                let sum = 0;
                for (const element of totalElements) {
                    sum += Number(element.innerHTML)
                }
                document.getElementById("total_price").innerHTML = sum.toString();
                let form = $form.get(0);
                let data = new FormData(form);
                let url = $form.attr('action');
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,

                    success: function(response) {
                        if (response['message'] == 'removed') {
                            window.location.href='/cart';
                        }

                        if (response['message'] == 'empty') {
                            window.location.href='/';
                        }
                    },
                });
            })
        });
    </script>
@endpush
