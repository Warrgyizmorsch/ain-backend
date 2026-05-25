<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Header styling */
        .header {
            width: 100%;
            background-color: #f5f5f5;
            padding: 20px;
            border-bottom: 2px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo styling */
        .logo h2 {
            text-align: center;
            margin: 0;
            font-size: 24px;
        }

        /* Contact information styling */
        .contact-info {
            text-align: right;
            font-size: 14px;
            line-height: 1.5;
        }

        .contact-info img {
            width: 20px; /* Adjust size for the icons */
            margin-right: 10px;
            vertical-align: middle;
        }

        /* Main content styling */
        .content {
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #555;
        }

        p {
            text-align: justify;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <!-- Company Logo -->
        <div class="logo">
            <h2>Assignment In Need</h2>
        </div>

        <!-- Contact Information -->
        <div class="contact-info">
            <p>
                <!-- Use asset() to reference the image paths -->
                <img src="{{ asset('assets/media/avatars/assignment_logo.png') }}" alt="Phone "> +91 7976940309<br>
                <img src="{{ asset('assets/media/avatars/assignment_logo.png') }}" alt="WhatsApp "> +44 2037695831
            </p>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <h1>{{ $sample->title }}</h1>
            <h2>Introduction</h2>
        <p>{!! $sample->content !!}</p>
    </div>
</body>
</html>
