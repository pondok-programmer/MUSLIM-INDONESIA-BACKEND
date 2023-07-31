<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #379237;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        header h1{
            color: #E2DA13
        }

        .container {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #379237
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            margin-bottom: 20px;
            font-size: 18px;
            text-align: center;
            color: #555;
        }

        a {
            display: block;
            width: 150px;
            margin: 0 auto;
            padding: 12px 24px;
            background-color: #379237;
            color: #E2DA13; /* Warna kuning kehijauan terang */
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        a p{
            color: #E2DA13;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }


        a:hover {
            background-color: rgba(69, 193, 69, 0.63);
        }

        footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <h1>Change Password</h1>
    </header>
    <div class="container">
        <h1>Assalamualaikum {{ $user->name }}!</h1>
        <p>Have you forgotten your password ? click here to reset your password</p>
        <a href="{{ $resetLink }}"><p>Reset Password</p></a>
    </div>
    <footer>
        <p>&copy; 2023 MuslimIndonesia. All rights reserved.</p>
    </footer>
</body>
</html>
