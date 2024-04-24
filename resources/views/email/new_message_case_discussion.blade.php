<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Message - {{$case->title}}</title>
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
        <h2>Hi There,</h2>
        <p>You got a new message for this <b>{{$case->title}}</b> case discussion.</p>
        <p>To reply back, please click the link below:</p>
        <p><a href="{{ route('case-discussion.index', ['id' => $case->uuid]) }}">{{$case->title}} Disscussion</a></p>
        <p>If you have any questions or need assistance, feel free to contact us.</p>
        <p>Best regards,<br>HRM PRO</p>
    </div>
</body>

</html>