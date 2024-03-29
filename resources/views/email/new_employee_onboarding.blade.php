<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Employee Onboarding</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        p {
            margin-bottom: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dear {{ $employeeName }},</h2>
        <p>Welcome to Our Company! We are thrilled to have you on board.</p>
        <p>To get started with your onboarding process, please click the link below:</p>
        <p><a href="{{ $onboardingLink }}">Employee Onboarding</a></p>
        <p>If you have any questions or need assistance, feel free to contact us.</p>
        <p>Best regards,<br>HRM PRO</p>
    </div>
</body>
</html>
