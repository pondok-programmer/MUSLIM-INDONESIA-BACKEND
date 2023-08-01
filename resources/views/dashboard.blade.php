<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 20px;
            font-family: Arial, sans-serif;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .user-details {
            font-size: 18px;
        }

        h1 {
            font-size: 32px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Welcome to Dashboard</h1>
                <div class="user-info">
                    <img src="{{ Auth::user()->photo }}" alt="Profile Picture" class="avatar">
                    <div class="user-details">
                        <p><strong>Name:</strong> {{ Auth::user()->full_name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->photo }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
