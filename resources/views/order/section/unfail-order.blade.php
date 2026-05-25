<script>
    function showConfirmation(orderId) {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, send AJAX request
                $.ajax({
                    url: '/unfail/' + orderId,
                    type: 'PUT', // Or 'GET' or 'POST' depending on your setup
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(response) {
                        // Handle success response
                        console.log(response);

                        // Reload the page and show success message
                        location.reload(true); // true forces a reload from the server

                        // You can also customize the SweetAlert success message here
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