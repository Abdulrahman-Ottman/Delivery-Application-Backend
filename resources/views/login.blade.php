<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ url('bootstrap.min.css') }}">

    <style>
        body {
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            backdrop-filter: blur(10px);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group label {
            position: absolute;
            top: 15px;
            left: 15px;
            transition: 0.2s ease all;
            color: #555;
            pointer-events: none;
        }

        .form-control {
            padding: 15px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .form-control:focus+label,
        .form-control:not(:placeholder-shown)+label {
            top: -15px;
            left: 10px;
            font-size: 12px;
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 18px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        @media (max-width: 500px) {
            .login-container {
                width: 90%;
            }
        }
    </style>
</head>

<body>
<div class="login-container">
    <h2>Login</h2>
    <form id="loginForm">
        <div class="form-group">
            <input type="tel" class="form-control" id="phone" placeholder=" " required>
            <label for="phone">Phone Number</label>
            <div class="error" id="phoneError"></div>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" placeholder=" " required>
            <label for="password">Password</label>
            <div class="error" id="passwordError"></div>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>--}}
<script src="{{ url('bootstrap.bundle.min.js')}}"></script>
<script>
    const url = 'http://192.168.9.54:8000';
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const phoneError = document.getElementById('phoneError');
    const passwordError = document.getElementById('passwordError');
    const phonePattern = /^\+\d{1,3}[-.\s]?\(?\d{1,4}\)?[-.\s]?\(?\d{1,4}\)?[-.\s]?\d{3,10}$/;
    const minPasswordLength = 8;

    function validatePhone() {
        if (!phoneInput.value.match(phonePattern)) {
            phoneError.textContent = 'Please enter a valid phone number.';
            return false;
        } else {
            phoneError.textContent = '';
            return true;
        }
    }

    function validatePassword() {
        if (passwordInput.value.length < minPasswordLength) {
            passwordError.textContent = 'Password must be at least 8 characters long.';
            return false;
        } else {
            passwordError.textContent = '';
            return true;
        }
    }

    phoneInput.addEventListener('input', validatePhone);
    passwordInput.addEventListener('input', validatePassword);

    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const isPhoneValid = validatePhone();
        const isPasswordValid = validatePassword();

        if (isPhoneValid && isPasswordValid) {
            try {
                const response = await fetch(`${url}/api/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        phone: phoneInput.value,
                        password: passwordInput.value,
                    }),
                });

                if (response.ok) {
                    const data = await response.json();
                    localStorage.setItem('access_token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    const userRole = data.user.role;
                    const store_id = data.user.store_id;

                    if (userRole == 'admin') {
                        window.location.href = `${url}/admin/dashboard/${store_id}`;
                    } else if (userRole == 'superAdmin') {
                        window.location.href = `${url}/super_admin/dashboard`;
                    } else {
                        alert('Role not recognized, please contact support.');
                    }
                } else {
                  //  const errorData = await response.json();
                    alert('Login failed. Invalid username or password.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            }
        }
    });
</script>
</body>
</html>
