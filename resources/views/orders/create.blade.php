@extends('welcome1')

@section('content')
    <h1>Your order is almost ready. Please just fill a few fields: </h1>
<form method="POST" action="{{route('order.store')}}">
    @csrf
    <label><b>Order information: </b></label><br>
    @foreach($orderData as $productId => $data)
        <div style="display: inline">
            <input type="text" name="name" value="{{$data['name']}}">
            <input type="text" name="img" value="{{$data['img']}}">
            <input type="text" name="quantity" value="{{$data['quantity']}}">
            <input type="text" name="total" value="{{$data['total']}}"> $
        </div>
        <br>
    @endforeach
    <br>
    <label><b>Select delivery: </b></label><br>
    <input type="radio" id="" name="deliver" value="myself" required checked="checked">Myself<br>
    <input type="radio" id="" name="deliver" value="novapashta">Novaposhta<br>
    <input type="radio" id="" name="deliver" value="ukrposhta">Ukrposhta<br>
    <label>Select payment method: </label><br>
    <input type="radio" id="" name="payment" value="cash" required checked="checked">Cash<br>
    <input type="radio" id="" name="payment" value="online">Online<br>
    <label><b>Provide address: </b></label><br>
    <input type="text" name="address" required><br><br>
<input type="submit" class="btn btn-dark" value="Confirm Order">
</form>
@endsection
