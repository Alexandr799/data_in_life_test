<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333333;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            background-color: #52b410;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .message {
            font-size: 18px;
            margin-top: 20px;
        }
        .name {
            color: #ffffff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Уведомление</h1>
        <p class="message">Здравствуйте <span class="name">{{ $userName }}</span>!
            Истекло время вашего участия в группе <span class="name">{{ $groupName }}</span>.
        </p>
    </div>
</body>
</html>
