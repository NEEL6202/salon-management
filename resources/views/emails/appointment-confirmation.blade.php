<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
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
            background-color: #007bff;
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
        .appointment-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
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
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Appointment Confirmed!</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $appointment->user->name }},</p>
        
        <p>Your appointment has been confirmed. Here are the details:</p>
        
        <div class="appointment-details">
            <h3>Appointment Details</h3>
            <p><strong>Salon:</strong> {{ $appointment->salon->name }}</p>
            <p><strong>Date:</strong> {{ $appointment->appointment_date->format('l, F d, Y') }}</p>
            <p><strong>Time:</strong> {{ $appointment->appointment_date->format('g:i A') }}</p>
            <p><strong>Service:</strong> {{ $appointment->service->name }}</p>
            <p><strong>Duration:</strong> {{ $appointment->service->duration }} minutes</p>
            <p><strong>Price:</strong> ${{ number_format($appointment->service->price, 2) }}</p>
        </div>
        
        <p>Please arrive 10 minutes before your scheduled appointment time.</p>
        
        <p>If you need to reschedule or cancel your appointment, please contact us at least 24 hours in advance.</p>
        
        <p>We look forward to seeing you!</p>
        
        <p>Best regards,<br>
        The {{ $appointment->salon->name }} Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Salon Management System. All rights reserved.</p>
    </div>
</body>
</html> 