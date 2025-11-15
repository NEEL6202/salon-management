<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expiring Soon</title>
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
        .subscription-details {
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
        <h1>Subscription Expiring Soon</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $subscription->salon->owner->name }},</p>
        
        <p>This is a reminder that your subscription for <strong>{{ $subscription->salon->name }}</strong> is expiring soon:</p>
        
        <div class="subscription-details">
            <h3>Subscription Details</h3>
            <p><strong>Plan:</strong> {{ $subscription->subscriptionPlan->name }}</p>
            <p><strong>Expiry Date:</strong> {{ $subscription->ends_at->format('l, F d, Y') }}</p>
            <p><strong>Amount:</strong> ${{ number_format($subscription->subscriptionPlan->price, 2) }}</p>
        </div>
        
        <p>To continue using our services without interruption, please renew your subscription before the expiry date.</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/salon/settings#subscription') }}" class="button">Renew Subscription Now</a>
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