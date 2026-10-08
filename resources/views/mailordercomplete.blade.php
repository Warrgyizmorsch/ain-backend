<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Your Assignment is Ready</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7ff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <!-- Hidden Preheader text for Email snippet preview -->
    <div style="display: none; max-height: 0px; overflow: hidden; font-size: 1px; line-height: 1px; color: #ffffff; opacity: 0; mso-hide: all;">
        Dear {{ $OrderData['name'] }}, your assignment titled "{{ $OrderData['title'] }}" (Order Code: {{ $OrderData['order_code'] }}) is now ready for your review.
    </div>
    <div style="display: none; max-height: 0px; overflow: hidden; font-size: 1px; line-height: 1px; color: #ffffff; opacity: 0; mso-hide: all;">
        &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy;
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color: #f4f7ff; padding: 20px 10px 40px 10px; width: 100%;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <tr>
                        <td align="center" style="background-color: #a797ff; padding: 25px;">
                            <img src="https://www.assignnmentinneed.com/assets/media/avatars/assignment_logo.png" alt="Assignment In Need" width="120" style="display: block; margin: 0 auto; border: 0; outline: none; text-decoration: none;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #333333;">
                            Dear {{ $OrderData['name'] }},<br>
                            Greetings of the day. I trust this message finds you well.<br><br>
                            We are pleased to inform you that your assignment titled <b>"{{ $OrderData['title'] }}"</b> (Order Code: <b>{{ $OrderData['order_code'] }}</b>) is now ready for your review.<br><br>
                            To proceed with the completion and delivery of your work, we kindly request the balance payment of <b>£{{ $OrderData['due'] }}</b>. Once the payment is received, we will promptly share the finalized assignment with you.<br><br>
                            <span style="color: #040309; font-weight: bold;">Special Offer – Save on Your Current Order :</span><br>
                            You can now avail an instant 20% discount on your current due amount by referring a friend to our services. Once your referred contact confirms an order with us, the discount will be applied immediately.<br><br>
                            <span style="color: #040309; font-weight: bold;">Terms &amp; Conditions:</span><br>
                            The discount is 20% of the current due amount, capped at a maximum of £20.
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #f8f9fa; padding: 20px; text-align: center; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #777777; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0 0 8px 0;">
                                <b>WhatsApp:</b> +44 7826233106 &nbsp;|&nbsp; <b>Email:</b> Order@assignnmentinneed.com
                            </p>
                            <p style="margin: 0;">
                                &copy; {{ date('Y') }} All Rights Reserved By <a href="https://www.assignnmentinneed.com/" target="_blank" style="color: #7860ff; text-decoration: none; font-weight: bold;">Assignment In Need</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>