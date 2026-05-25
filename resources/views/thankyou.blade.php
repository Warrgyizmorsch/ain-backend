<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thank You | Assignment In Need</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .thank-you-box {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            animation: fadeIn 1.2s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .thank-you-box i {
            font-size: 70px;
            color: #28a745;
            margin-bottom: 30px;
            animation: bounce 1.2s ease infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        .thank-you-box h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 42px;
            color: #333;
            margin-bottom: 20px;
        }

        .thank-you-box p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .login-form {
            text-align: center;
        }

        .login-form a {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .login-form a:hover {
            background-color: #218838;
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
        }

        .footer-text {
            font-size: 14px;
            color: #888;
            margin-top: 25px;
        }

        .footer-text a {
            color: #28a745;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-text a:hover {
            color: #218838;
        }

        /* Flash message styles */
        .flash-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .flash-message.show {
            display: block;
        }

        @media (max-width: 768px) {
            .thank-you-box {
                padding: 30px;
            }

            .thank-you-box h1 {
                font-size: 34px;
            }

            .thank-you-box p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="thank-you-box">
        <i class="fas fa-check-circle"></i>
        <h1>Thank You!</h1>
        <p>Your order has been successfully placed. We appreciate your business and look forward to serving you again!</p>

        <!-- Flash message section -->
       
        @if(session('email'))
            <p>login your account and check your order status 
            </br>
           <b>  email: {{ session('email') }}</b>
            </br>
           <b> password : user@123 (until you change) </b>
            </p>
        @endif
        <div class="login-form">
            <a href="/login">Go to Your Account</a>
        </div>

        <p class="footer-text">Need assistance? <a href="#">Contact Support</a></p>
    </div>

    <!-- JavaScript to show flash message -->
    
</body>

</html>
