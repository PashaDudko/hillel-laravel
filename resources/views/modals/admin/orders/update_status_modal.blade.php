
<!-- The Modal -->
<div id="orderStatus" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="order-update" method="POST" action="{{route('admin.orders.update', $order->id)}}">
            @csrf
            @method('PUT')
            <label for="status"> Set order status: </label>
            <select name="status" id="order_status_select" class="form-control">
                @foreach($statuses as $name => $value)
                    <option
                        value="{{ $value }}"
                        @selected($value == $order->status->value)
                    >
                        {{ ucfirst(strtolower($name)) }}
                    </option>
                @endforeach
            </select>
            <br>
            <label for="estimated_delivery_date">Estimated delivery date:</label>
            <input type="date" name="estimated_delivery_date" value="{{$order->estimated_delivery_date}}" id="estimated_delivery_date" class="form-control">
            <br>
            <button type="submit">Update</button>
        </form>
    </div>
</div>
<script>
    // Get the modal
    const modal = document.getElementById("orderStatus");

    // Get the button that opens the modal
    const btn = document.getElementById("status_btn");

    // Get the <span> element that closes the modal
    const span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    const form = document.getElementById('order-update');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const selectElement = document.getElementById('order_status_select');
            const newStatus = selectElement.value;
            let data = new FormData(form);
            let url = $(this).attr('action');
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "JSON",
                processData: false,
                contentType: false,

                success: function(response) {
                    if (response['message'] == 'updated') {
                        const statusField = document.getElementById('current_order_display_status');
                        statusField.textContent = newStatus;
                        const dateField = document.getElementById('delivery_date');
                        if (document.getElementById('estimated_delivery_date').value) {
                            dateField.textContent = document.getElementById('estimated_delivery_date').value
                        }
                        modal.style.display = "none";
                    }
                },
            });

        });
    } else {
        console.error('error');
    }
</script>
<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
        display: flex;
    }

    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        margin-left: auto;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
