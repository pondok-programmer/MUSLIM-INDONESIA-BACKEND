<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    input {
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    button {
      padding: 10px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #45a049;
    }

    .google-login {
      background-color: #DB4437;
      margin-top: 10px;
    }

    .google-login:hover {
      background-color: #d62017;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login Form</h2>
    <form>
      <label for="email">Email:</label>
      <input type="email" id="email" placeholder="Enter your email" required>
      <label for="password">Password:</label>
      <input type="password" id="password" placeholder="Enter your password" required>
      <button type="submit">Login</button>
      <a href="{{ url('/login/google') }}" class="google-login" role="button">Login with Google</a>
    </form>
  </div>
</body>
</html>
