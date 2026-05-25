<script>
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
</script>
