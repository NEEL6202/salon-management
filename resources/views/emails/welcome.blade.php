<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Salon Management System</title>
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
            background-color: #28a745;
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
        .welcome-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
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
            background-color: #28a745;
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
        <h1>Welcome to Salon Management System!</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <p>Welcome to the Salon Management System! We're excited to have you on board.</p>
        
        <div class="welcome-details">
            <h3>Your Account Details</h3>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            @if($user->salon)
            <p><strong>Salon:</strong> {{ $user->salon->name }}</p>
            @endif
            <p><strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->roles->first()->name ?? 'User')) }}</p>
        </div>
        
        <p>Here's what you can do with your account:</p>
        <ul>
            @if($user->hasRole('super_admin'))
            <li>Manage all salons and users on the platform</li>
            <li>Configure system settings and subscription plans</li>
            <li>View platform analytics and reports</li>
            @elseif($user->hasRole('salon_owner'))
            <li>Manage your salon's services, products, and employees</li>
            <li>View appointments, orders, and customer information</li>
            <li>Configure your salon's settings and subscription</li>
            @elseif($user->hasRole('manager') || $user->hasRole('employee'))
            <li>Manage appointments and customer interactions</li>
            <li>Access your schedule and performance reports</li>
            <li>View customer information and service history</li>
            @elseif($user->hasRole('customer'))
            <li>Book appointments and purchase products</li>
            <li>View your appointment history and order records</li>
            <li>Manage your profile and preferences</li>
            @endif
        </ul>
        
        <p style="text-align: center;">
            <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
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