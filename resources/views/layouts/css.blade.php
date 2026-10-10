
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

			/* CRM Action Buttons: WhatsApp & Email */
			.crm-btn-wa {
				width: 28px !important;
				height: 28px !important;
				border-radius: 6px !important;
				background: #e8fff3 !important;
				border: 1px solid #b7f5d0 !important;
				display: inline-flex !important;
				align-items: center !important;
				justify-content: center !important;
				padding: 0 !important;
				transition: all 0.2s ease !important;
			}
			.crm-btn-wa svg {
				fill: #25D366 !important;
				transition: fill 0.2s ease !important;
			}
			.crm-btn-wa:hover {
				background: #25D366 !important;
				border-color: #25D366 !important;
				transform: scale(1.08);
			}
			.crm-btn-wa:hover svg {
				fill: #ffffff !important;
			}

			.crm-btn-email {
				width: 28px !important;
				height: 28px !important;
				border-radius: 6px !important;
				background: #fff5f8 !important;
				border: 1px solid #fcdde4 !important;
				display: inline-flex !important;
				align-items: center !important;
				justify-content: center !important;
				padding: 0 !important;
				transition: all 0.2s ease !important;
			}
			.crm-btn-email svg {
				fill: #f1416c !important;
				transition: fill 0.2s ease !important;
			}
			.crm-btn-email:hover {
				background: #f1416c !important;
				border-color: #f1416c !important;
				transform: scale(1.08);
			}
			.crm-btn-email:hover svg {
				fill: #ffffff !important;
			}

			/* Tag Button & General Hover Icon Visibility Fixes */
			.crm-btn-tag {
				transition: all 0.2s ease !important;
			}
			.crm-btn-tag i {
				color: #50cd89 !important;
				transition: color 0.2s ease !important;
			}
			.crm-btn-tag:hover {
				background-color: #50cd89 !important;
				border-color: #50cd89 !important;
			}
			.crm-btn-tag:hover i,
			.btn-light-success:hover i,
			.btn-light-success:hover .text-success {
				color: #ffffff !important;
			}
			.btn-light-danger:hover i,
			.btn-light-danger:hover .text-danger {
				color: #ffffff !important;
			}
		</style>
		<!--end::CRM Data Security-->