@extends('welcome1')

@section('content')
    <h1>Your order is almost ready. Please just fill a few fields: </h1>

    <label><b>Order information: </b></label>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Product</th>
            <th scope="col">Quantity</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody>
            @foreach($orderData as $productId => $data)
                <tr>
                    <td>
                        {{$loop->index +1}}
                    </td>
                    <td>
                        <img width="100" height="100" src="{{asset($data['img'])}}" alt="" title="">
                    </td>
                    <td>
                        <input type="text" name="name" value="{{$data['name']}}">
                    </td>
                    <td>
                        <input type="text" name="quantity" value="{{$data['quantity']}}">
                    </td>
                    <td>
                        <input type="text" name="total" value="{{$data['total']}}"> $
                    </td>
                <br>
            @endforeach
        </tbody>
    </table>
    <b>Total Cost: </b> <input type="number" value="{{$totalPrice}}"> $
    <br>
    <br>
    <form method="POST" action="{{route('order.store')}}">
        @csrf
        <label><b>Select delivery: </b></label><br>
        <input type="radio" id="" name="deliver" value="myself" required checked="checked">Myself<br>
        <input type="radio" id="" name="deliver" value="novapashta">Novaposhta<br>
        <input type="radio" id="" name="deliver" value="ukrposhta">Ukrposhta<br>
        <label>Select payment method: </label><br>
        <input type="radio" id="" name="payment" value="cash" required checked="checked">Cash<br>
        <input type="radio" id="" name="payment" value="online">Online<br>
        <label><b>Provide address: </b></label><br>
        <input type="text" name="shipped_address" required><br>
        <label><b>Want something to add: </b></label><br>
        <input type="text" name="comment"><br><br>
        <input type="submit" class="btn btn-dark" value="Confirm Order">
    </form>
@endsection
