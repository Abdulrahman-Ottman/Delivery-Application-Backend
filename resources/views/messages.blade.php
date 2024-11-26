<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Service</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        header p {
            font-size: 1rem;
            margin: 0;
            font-weight: 300;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4CAF50;
            font-size: 2rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #4CAF50;
            display: inline-block;
            padding-bottom: 5px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #f5f5f5;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li span {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>

<header>
    <h1>SMS Service</h1>
</header>

<div class="container">
    <h2>Sent Messages :</h2>
    <ul>
        <li>
            @if(isset($messages['phone']))
                <span>To:</span> {{ $messages['phone'] }}
                <span>Code:</span> {{ $messages['code'] }}
            @else
                <span>No available messages for your number.</span>
            @endif
        </li>
    </ul>
</div>A

</body>
</html>
