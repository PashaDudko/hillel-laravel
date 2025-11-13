@extends('welcome1')

@section('content')
    <div class="container">
        <img width="300" height="300" src="{{asset($product->images()->first()->path)}}" alt="" title="">
        <div class="row justify-content-center">
            <div id="res"></div>
            <br>
            <br>
            <h1> Product: <b>{{$product->name}}</b> </h1>
            <i>{{$product->description}}</i>
            <ul>
                <li><b>SKU:</b> {{$product->SKU}}</li>
                <li><b>PRICE:</b> {{$product->price}}</li>
                <li><b>In stock:</b> {{$product->in_stock}}</li>
            </ul>
        </div>
        <form id="cart" method="POST" action="{{ route('cart.add') }} ">
            @csrf
            <input type="number" class="form-control" hidden name="product_id" value="{{$product->id}}">
            <input type="number" class="form-control" hidden name="price" value="{{$product->price}}">
            <input type="number" class="form-control" hidden name="quantity" value=1>
            <button type="submit" class="btn btn-dark" id="cart_submit_btn">ADD TO CART</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // ADD TO CART script
        $(document).ready(function(){
            $('#cart').submit(function(e){
                e.preventDefault();
                let form = $('#cart')[0];
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
                        if (response.code == 200) {
                            let successPopup = '<span class="alert alert-success">Product is added to Cart</span>'
                            $("#res").html(successPopup);
                            // $("#cartImg").attr("src");
                            $("#res").delay(2500).fadeOut();
                            // $("#res").delay(2000).html("");
                            $('#cart-img').attr('src', '/storage/other/cart_not_empty.jpg');
                        } else {
                            console.log('Some error occurred');
                        }

                    },
                });
            })
        })
    </script>
@endpush
