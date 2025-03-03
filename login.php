<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #01012F;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            width: 800px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 40px;
        }
        .logo-section {
            flex: 0 0 250px;
            padding-right: 40px;
            border-right: 1px solid #eee;
        }
        .logo-section img {
            width: 100%;
            height: auto;
        }
        .form-section {
            flex: 1;
            text-align: center;
        }
        h2 {
            font-size: 2.5rem;
            font-weight: bold; /* เพิ่มความหนาของตัวอักษร */
            color: #283890;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 2rem;
            text-transform: uppercase;
        }
        h2:after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: #89C6FF;
            margin: 0.5rem auto 0;
            border-radius: 2px;
        }
        .form-group {
            margin-bottom: 20px;
            
        }
        input[type="text"], input[type="password"] {
            width: auto;
            min-width: 325px;
            max-width: 250px;
            padding: 0.8rem 1.2rem;
            font-size: 1rem;
            border: 2px solid #e0e0e0;
            letter-spacing: 0.5px;
            border-radius: 25px;
            outline: none;
            text-align: center;
            color: #555;
            background-color: #f9f9f9;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #FFC107;
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
        }
        .login-button {
            background-color: #89C6FF;
            color:rgb(0, 0, 0);
            border: 2px solid #000; /* เพิ่มขอบสีดำ */
            padding: 12px 30px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
            width: 30%;
            margin-top: 15px;
            
        }
        .login-button:hover {
            background-color:rgb(87, 174, 255);
            transform: translateY(-2px);
        } 
        .diy-text h3 {
            color:rgb(255, 255, 255); 
            font-family: 'Source Sans 3', system-ui, -apple-system, sans-serif;
            font-size: 70px;
            margin-bottom: 40px;
            margin-top: -40px;
            text-align: center;
            text-shadow: 5px 5px 5px #000; /* เพิ่มขอบอักษรสีดำ */
        }
    </style>
</head>
<body>
<div class="diy-text">
        <h3>IT SYSTEM</h3>
    
    <div class="login-container">
        <div class="logo-section">
            <img src="assets/img/mrdiy_logo.jpg" alt="MR. DIY Logo">
        </div>
        <div class="form-section">
            <h2>LOGIN</h2>
            <form method="POST" action="process/login_action.php">
                <div class="form-group">
                    <input type="text" name="username" placeholder="UserName" required maxlength="32">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required maxlength="32">
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </div>
    </div>
</body>
</html>