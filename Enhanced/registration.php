<?php 
    require 'security_config.php'; 
    startSecureSession();  
    $csrf_token = generateCsrfToken();  
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="javascript/validation.js"></script>
</head>

<body>
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
                <h1 class="title-logo">Sign Up</h1><br>
                <?php if (isset($_SESSION['error'])): ?>
                    <p class="text-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>
                
                <form name="signupForm" action="register.php" method="POST" onsubmit="return validateForm()">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <label for="username">Username:</label>
                    <input type="text" name="username" class="form-control" id="username" pattern="[a-zA-Z0-9_]{3,20}$" autocomplete="off" required><br>
                    
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$" autocomplete="off" required><br>

                    <label for="role">Role:</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <?php
                            require 'db.php';
                            $stmt = $pdo->query('SELECT * FROM roles');
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                        ?>
                    </select><br>

                    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                    <p class="mt-3 text-center">Already have an account? <a href="index.php">Login</a></p>
                </form>
            </div>
        </section>
    </section>
</body>
</html>
