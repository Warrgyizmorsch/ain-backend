<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Drawer</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        /* CSS styles */
    </style>
</head>
<body>

<!-- Button to open the chat drawer -->
<button class="btn btn-call">Open Chat</button>

<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close" style="display:none;">
    <!-- Chat drawer content -->
</div>

<script>
    $(document).ready(function () {
        // Function to handle opening chat drawer
        function openChatDrawer() {
            $('#kt_drawer_chat').addClass('show');
        }

        // Click event handler for the "Call" button
        $('.btn-call').click(function () {
            openChatDrawer();
        });
    });
</script>

</body>
</html>
