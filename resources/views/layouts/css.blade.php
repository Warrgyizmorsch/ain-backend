
		<!-- <link rel="shortcut icon" href="/assets/media/avatars/assignment_logo.png" /> -->
		<!--begin::Fonts-->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<!--end::Fonts-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

		<!--begin::CRM Data Security - Disable Selection & Copy-->
		<style>
			/* Disable text selection across CRM for data protection */
			body, body * {
				-webkit-user-select: none !important;
				-moz-user-select: none !important;
				-ms-user-select: none !important;
				user-select: none !important;
			}

			/* Allow text selection & typing inside input fields, textareas, and rich-text editors */
			input, 
			textarea, 
			[contenteditable="true"], 
			.select2-search__field, 
			.note-editable, 
			.form-control, 
			.allow-select {
				-webkit-user-select: text !important;
				-moz-user-select: text !important;
				-ms-user-select: text !important;
				user-select: text !important;
			}
		</style>
		<!--end::CRM Data Security-->