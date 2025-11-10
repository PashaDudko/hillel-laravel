@extends('layouts.admin')
{{--@extends('layouts.app')--}}{{-- js script did not work, so add '@stack('scripts')' to admin layout--}}
{{--@extends('welcome1')--}}

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> ORDERS </h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">User</th>
                    <th scope="col">Number</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{$loop->index + 1}}</td>
                    <td>{{$order->id}}</td>
                    <td><a href="{{route('admin.users.show', $order->user->id)}}">{{$order->user->name}} {{$order->user->lastname}}</a></td>
                    <td>{{$order->number}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>{{$order->status}}</td>
                    <td>
                        <a href="{{route('admin.orders.show', $order->id)}}"><i class="fa fa-eye"></i></a>,
                        <form method="POST" action="{{route('admin.orders.destroy', $order->id)}}">
                            @csrf
                            @method('DELETE')
                            <button><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                    @if ($order->comment)
                        <tr>
                            <td>
                                <button type="button" class="toggle-comment" onclick="showHideComment(this);">Show comment</button>
                            </td>
                        </tr>
                        <tr class="comment-row order-{{ $order->id }}" style="display: none;">
                            <td colspan="5">
                                <div class="comment-details">
                                    <p><strong>{{$order->comment}}</strong></p>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
@endsection
@push('scripts')
    <script>
        function showHideComment(buttonElement) {
            const currentText = buttonElement.textContent;
            const row = buttonElement.closest('tr');
            const commentRow = row.nextElementSibling;

            if (commentRow && commentRow.classList.contains('comment-row')) {
                if (commentRow.style.display === 'none') {
                    commentRow.style.display = 'table-row';
                    buttonElement.textContent = 'Hide comment';
                } else {
                    commentRow.style.display = 'none';
                    buttonElement.textContent = 'Show comment';
                }
            }
        }
    </script>
@endpush
