{{-- <script>
    function showConfirmation(orderId, isFail) {
        let title, confirmButtonText, action;

        if (isFail == 1) { // If the order is already failed
            title = 'Are you sure you want to cancel the failed status of this order?';
            confirmButtonText = 'Yes, cancel it!';
            action = 'cancel';
        } else { // If the order is not failed
            title = 'Are you sure you want to mark this order as failed?';
            confirmButtonText = 'Yes';
            action = 'fail';
        }

        Swal.fire({
            title: title,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, send AJAX request
                $.ajax({
                    url: '/update-status/' + orderId,
                    type: 'PUT', // Or 'GET' or 'POST' depending on your setup
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle success response
                        location.reload(true); // true forces a reload from the server
                        Swal.fire({
                            icon: 'success',
                            title: 'Order updated successfully!',
                            showConfirmButton: false,
                            timer: 1500 // Show the success message for 1.5 seconds
                        });
                    },
                    error: function(error) {
                        // Handle error response
                        console.error(error);
                    }
                });
            }
        });
    }
</script> --}}

<script>
    // --- NAYA LOGIC: Page load hone par check karega ki kaunse orders ATL hain ---
    $(document).ready(function() {
        $('[id^="order-cell-"]').each(function() {
            // ID se orderId nikalna (jaise 'order-cell-15' me se '15' nikalna)
            let orderId = $(this).attr('id').replace('order-cell-', '');

            // Agar local storage me ye order ATL mark hai, toh lal kar do
            if (localStorage.getItem('atl_order_' + orderId) === 'true') {
                $(this).css({
                    'background-color': '#ffcccc',
                    'border': '2px solid red'
                });
            }
        });
    });

    function showConfirmation(orderId, isFail) {
        let title, confirmButtonText, action;

        if (isFail == 1) {
            title = 'Are you sure you want to cancel the failed status?';
            confirmButtonText = 'Yes, cancel it!';
            action = 'cancel';
        } else {
            title = 'Are you sure you want to mark this order as failed?';
            confirmButtonText = 'Yes';
            action = 'fail';
        }

        Swal.fire({
            title: title,
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: isFail != 1,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            denyButtonColor: '#ffc107',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'No',
            denyButtonText: 'ATL'
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: '/update-status/' + orderId,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("FULL RESPONSE:", response);
                        console.log("LOG:", response.log);
                        Swal.fire({
                            icon: 'success',
                            title: 'Order updated successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Agar successfully status badal gaya, toh isko ATL se hata do (optional)
                            localStorage.removeItem('atl_order_' + orderId);
                            location.reload(true);
                        });
                    },
                    error: function(error) {
                        console.error("Error updating status:", error);
                    }
                });
            } else if (result.isDenied) {
                // --- NAYA LOGIC: ATL ko Local Storage me save karna ---
                localStorage.setItem('atl_order_' + orderId, 'true');

                // Color change kar do
                $('#order-cell-' + orderId).css({
                    'background-color': '#ffcccc',
                    'border': '2px solid red'
                });
            }
        });
    }
</script>