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
                <script>
                    {{--let product_id = "{{$item['product_id']}}";--}}
                    let product_name = "{{$item['slug']}}";
                    let in_stock = "{{$item['in_stock']}}";
                </script>
                <tr>
                    <td>
                        {{$loop->index + 1}}
                    </td>
                    <td>
                         {{$item['product_name']}}
                    </td>
                    <td>
                        <span id="product_price">{{$item['price']}}</span>
                    </td>
                    <td>
                        <form id="cart-update" method="POST" action="{{ route('cart.update') }} ">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$item['product_id']}}">
                            <input type="hidden" name="new_quantity" id="new_quantity" value="">
                            <button id="plus" type="submit" class="btn border-2 btn-outline-dark" onclick="changeProductQuantityInCart(product_name, 'plus', in_stock);">+</button>
{{--                            <span id="current_quantity">{{$item['quantity']}}</span>--}}
                            <span id="{{$item['slug']}}">{{$item['quantity']}}</span>
{{--                            <input type="number" id="current_quantity" value="{{$item['quantity']}}" min="0" max="10"> если тип поля намбер - то там уж есть счетчик и есть ограничения--}}
                            <button id="minus" type="submit" class="btn border-2 btn-outline-dark" onclick="changeProductQuantityInCart(product_name, 'minus', in_stock);">-</button>
                        </form>
                    </td>
                    <td>
                        <span id="total">{{$item['price'] * $item['quantity']}}</span> $
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
            <th scope="row">Total Price: <span id="total_price">{{$item['price']}}</span> $</th>
            </tbody>
        </table>
        <button class="btn btn-dark" onclick="window.location.href = '/order/create'">Create Order</button>
    </div>
@endsection
{{--https://ru.wordpress.org/plugins/wc-quantity-plus-minus-button/--}}

@push('scripts')
    {{--    <script src="/resources/js/test.js"></script> //не удается вынести в отдельный файл--}}
    <script>
        // UPDATE PRODUCT QUANTITY IN CART script
        function changeProductQuantityInCart(product_name, sign, in_stock) {
            // alert(Number(document.getElementById(product_name).innerHTML));
            let current_quantity = Number(document.getElementById(product_name).innerHTML);
            let product_price = Number(document.getElementById("product_price").innerHTML);
            if (sign == 'plus') {
                current_quantity++;
                if (current_quantity > in_stock) {
                    current_quantity = in_stock;
                }
            } else {
                current_quantity--;
                if (current_quantity < 0) {
                    current_quantity = 0;
                }
            }

            document.getElementById(product_name).innerHTML = current_quantity.toString();
            document.getElementById("new_quantity").value = current_quantity.toString();
            document.getElementById("total").innerHTML = (current_quantity * product_price).toString();
        }
        $(document).ready(function(){
            $('#cart-update').submit(function(e){
                e.preventDefault();
                document.getElementById("total_price").innerHTML = document.getElementById("total").innerHTML;
                let form = $('#cart-update')[0];
                let data = new FormData(form);
                let url = $(this).attr('action')

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
        })
    </script>
@endpush
