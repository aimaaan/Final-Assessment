<?php
    require 'security_config.php'; 
    startSecureSession();  
    $csrf_token = generateCsrfToken();    

    $error_message = isset($_SESSION['error']) ? htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') : '';
    unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log in</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css" />
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+e/JpG/6ne0xZKI9M/lg5vLa5k8mN" crossorigin="anonymous"></script>
    <script src="javascript/validation.js"></script>
</head>

<body>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = '<?php echo $error_message; ?>';
            if (errorMessage) {
                var alertPlaceholder = document.getElementById('alertPlaceholder');
                var wrapper = document.createElement('div');
                wrapper.innerHTML = '<div class="alert alert-danger alert-dismissible" role="alert">' + 
                                    errorMessage + 
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                alertPlaceholder.append(wrapper);
            }
        });
    </script>

    <section>
        <nav class="nav-container">
            <div class="logo-container">
                <img class="logo-img" src="Image/Hotel logo.png" />
                <h4>Flower Hotel</h4>
            </div>
            <input type="checkbox" id="click" />
            <label for="click" class="menu-btn">
                <i class="fas fa-bars"></i>
            </label>
        </nav>

        <section class="flexbox-container">
            <div class="flexbox f1">
                <img id="music-btn" class="logo-img1" src="Image/Hotel logo.png" />
            </div>
            
            <div class="flexbox f2">
                <h1 class="title-logo">Log In</h1><br>
                <div id="alertPlaceholder"></div>
                <form action="login.php" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <label for="username">Username:</label>
                    <input 
                        type="text"
                        class="form-control" 
                        id="username" 
                        name="username" 
                        pattern="[a-zA-Z0-9_]{3,20}$" 
                        autocomplete="off" required> <br>

                    <label for="password">Password:</label>
                    <input 
                        type="password"
                        class="form-control" 
                        id="password" 
                        name="password" 
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$" 
                        autocomplete="off" required><br>
                    
                    <div class="form-group">
                        <label for="google2fa_code">Verify:</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="google2fa_code" 
                            name="google2fa_code" 
                            placeholder="Enter 2FA Code" 
                            autocomplete="off" required>
                    </div><br>
                    
                    <button type="submit" name="login" id="login" class="btn btn-primary w-100">Login</button>
                    <p class="mt-3 text-center">Don't have an account? <a href="registration.php">Sign up</a></p>
                </form>
            </div>
        </section>
    </section>
</body>
</html>
