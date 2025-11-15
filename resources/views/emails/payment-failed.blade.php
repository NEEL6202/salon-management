<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .payment-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Failed</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $subscription->salon->owner->name }},</p>
        
        <p>We're sorry to inform you that a payment for your subscription at <strong>{{ $subscription->salon->name }}</strong> has failed:</p>
        
        <div class="payment-details">
            <h3>Payment Details</h3>
            <p><strong>Plan:</strong> {{ $subscription->subscriptionPlan->name }}</p>
            <p><strong>Amount:</strong> ${{ number_format($subscription->subscriptionPlan->price, 2) }}</p>
            <p><strong>Date:</strong> {{ now()->format('l, F d, Y') }}</p>
        </div>
        
        <p>To avoid interruption of your services, please update your payment method as soon as possible.</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/salon/settings#payment') }}" class="button">Update Payment Method</a>
        </p>
        
        <p>If you have any questions or need assistance, please contact our support team.</p>
        
        <p>Best regards,<br>
        The Salon Management Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Salon Management System. All rights reserved.</p>
    </div>
</body>
</html>