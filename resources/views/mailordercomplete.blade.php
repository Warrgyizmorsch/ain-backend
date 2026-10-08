<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Assignment is Ready</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7ff; margin: 0; padding: 0; -webkit-text-size-adjust: none; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7ff; padding-bottom: 40px; }
        .main-table { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; border: 1px solid #e0e0e0; }
        .header { background-color: #a797ff; padding: 25px; text-align: center; }
        .content { padding: 40px 30px; line-height: 1.6; color: #333333; font-size: 15px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #777777; border-top: 1px solid #eeeeee; }
        .highlight { color: #040309; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main-table" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="header">
                    <img src="https://www.assignnmentinneed.com/assets/media/avatars/assignment_logo.png" alt="Assignment In Need" width="120" style="display: block; margin: 0 auto;">
                </td>
            </tr>

            <tr>
                <td class="content">
                    Dear {{ $OrderData['name'] }},<br>
                    Greetings of the day. I trust this message finds you well.<br><br>
                    We are pleased to inform you that your assignment titled <b>"{{ $OrderData['title'] }}"</b> (Order Code: <b>{{ $OrderData['order_code'] }}</b>) is now ready for your review.<br><br>
                    To proceed with the completion and delivery of your work, we kindly request the balance payment of <b>£{{ $OrderData['due'] }}</b>. Once the payment is received, we will promptly share the finalized assignment with you.<br><br>
                    <span class="highlight">Special Offer – Save on Your Current Order :</span><br>
                    You can now avail an instant 20% discount on your current due amount by referring a friend to our services. Once your referred contact confirms an order with us, the discount will be applied immediately.<br><br>
                    <span class="highlight">Terms & Conditions:</span><br>
                    The discount is 20% of the current due amount, capped at a maximum of £20.
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p style="margin-bottom: 5px;">
                        <b>WhatsApp:</b> +44 7826233106 | <b>Email:</b> Order@assignnmentinneed.com
                    </p>
                    <p>&copy; {{ date('Y') }} All Rights Reserved By <a href="https://www.assignnmentinneed.com/" style="color: #7860ff; text-decoration: none;">Assignment In Need</a></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>