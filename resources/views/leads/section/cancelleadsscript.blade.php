<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function handleChange(checkbox, leadId) {
        if (!checkbox.unchecked) {
            // Show SweetAlert when the checkbox is unchecked
            Swal.fire({
                title: 'Activate Lead',
                text: 'Are you sure you want to Activate this lead?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Activate !'
            }).then((result) => {
                // If the user clicks "Yes," send the lead ID to the controller
                if (result.isConfirmed) {
                    // You can use AJAX to send the lead ID to the controller
                    // For example, using jQuery AJAX
                    $.ajax({
                        url: 'active_leads' + leadId,
                        method: 'PUT',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                        success: function(response) {
                            // Handle the response from the controller
                            // console.log(response);
                            location.reload(true); // true forces a reload from the server
                            Swal.fire({
                            icon: 'success',
                            title: 'Leads updated successfully!',
                            showConfirmButton: false,
                            timer: 1500 // Show the success message for 1.5 seconds
                        });
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                } else {
                    // If the user clicks "No" or closes the SweetAlert
                    // You can handle it here if needed
                    checkbox.checked = false;
                }
            });
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   document.addEventListener('DOMContentLoaded', function () {
      const deleteLinks = document.querySelectorAll('.delete-link');

      deleteLinks.forEach(link => {
         link.addEventListener('click', function (event) {
            event.preventDefault();

            const leadId = this.getAttribute('id');

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
               // If user confirms, send AJAX request to delete route
               if (result.isConfirmed) {
                $.ajax({
                        url: 'Delete' + leadId,
                        method: 'Delete',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                            // Handle the response from the controller
                            // console.log(response);
                            location.reload(true); // true forces a reload from the server
                            Swal.fire({
                            icon: 'success',
                            title: 'Leads Deleted successfully!',
                            showConfirmButton: false,
                            timer: 15000 // Show the success message for 1.5 seconds
                           
                        }); 
                    },
                        error: function(error) {
                            console.error(error);
                        }
               })
            }
            });
         });
      });
   });
</script>
