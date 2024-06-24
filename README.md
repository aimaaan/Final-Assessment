# Final-Assessment
Web Application Security Enhancement Final Assessment Group Project

## Group Name
Seeker

## Group Members
1. Ahmad Arif Aiman bin Ahmad Fauzi (2113419)
2. Muhammad Nasrullah bin Mat Radzi (2013677)
3. Muhammad Zafran bin Zamani (2110893)

## Title
Flower Hotel

## Introduction
Improved version of Flower Hotel web app with security features added onto the original web technologies class project. <br>
[Original](Original) owners are:
- Muhammad Faris Bin Musa (2013259)
- Nor Zuhayra Amalin binti Zulkifli (2011642)
- Wan Nurshafiqah Nabila binti Wan Masri (2013674)
- Muhammad Nasrullah bin Mat Radzi (2013677)
- Muhammad Haikal bin Azhari (2014711)

'Flower Hotel' is a website for a hotel that based in Malaysia.

## Objectives
1. To authenticate and authorize valid user that can book their hotel room through the website.
2. To prevent unauthorize access by implementing session management.
3. To implement Regex and input validation to prevent SQL injection and XSS in the text box especially in the login and register page.
4. File directory cannot be accessed by unauthorize user since it has been disabled.
5. To prevent CSRF by implementing Anti-CSRF token and secure session management.
6. To create a safer environment for the user to access and use the website.

## Task Distribution 
| Enhancement | Assigned |
| ------------- | ------------- |
| Authentication & Authorization | Aiman |
| Input validation & File security principle | Nasrullah |
| XSS and CSRF prevention & Database security principles | Zafran |

## Enhancement
The authors of the file additions/enhancements are encased in square brackets as such: 
- [aiman] refers to [Ahmad Arif Aiman bin Ahmad Fauzi](https://github.com/aimaaan)
- [Nasrullah] refers to [https://github.com/MuhdNasrullah](https://github.com/MuhdNasrullah)
- [zafran] refers to [Muhammad Zafran bin Zamani](https://github.com/zafranzamani)

### 1. Authentication [aiman]
   #### a. Securing password-based authentication using hashed password and generate secret key for 2FA
   - the password complexity are set Numbers + Lowercase Letters + Uppercase letters + symbols + at least 12 characters by using regex: ``'/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{12,}$/'`` at [register.php](Enhanced/register.php)
   - password are hashed using ``password_hash($password, PASSWORD_DEFAULT);`` in [register.php](Enhanced/register.php) & verify ``password_verify($password, $user['password']))`` at [login.php](Enhanced/login.php)

   #### b. Disable password with the same username and reusing the same password as previous password if same username registered
   - Password must not be the same as username at [register.php](Enhanced/register.php)
   - Password are disable to reuse the same password as previous if the same username register again in [register.php](Enhanced/register.php)

     ```php
      $password_pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{12,}$/';
          if (empty($password)) {
              $error_password = "Password is required.";
          } elseif (!preg_match($password_pattern, $password)) {
              $error_password = "Password must be at least 12 characters long and include at least one number, one lowercase letter, one uppercase letter, and one special character.";
          } elseif ($password === $username) {
              $error_password = "Password cannot be the same as the username.";
          } else {
              $stmt = $pdo->prepare('SELECT password FROM previous_passwords WHERE username = :username');
              $stmt->bindParam(':username', $username, PDO::PARAM_STR);
              $stmt->execute();
              $previous_passwords = $stmt->fetchAll(PDO::FETCH_COLUMN);
              foreach ($previous_passwords as $previous_password) {
                  if (password_verify($password, $previous_password)) {
                      $error_password = "You cannot reuse a previous password.";
                      break;
                  }
              }
          }
        ```
     ![previouspassword](https://github.com/aimaaan/Final-Assessment/assets/99475237/626195b1-d788-429e-94c2-cd2019fa77bc)

     Everytime user register, password are stored in ``previous_passwords table`` with timeframe. If same user want to register, it will refer to this table and disable same password for registration. 
      
      #### c. Enable account lockout and disable autocomplete 
      - Allow account to be lockout if failed attempt more than 5 times and timeframe are recorded in the database. 
      - The duration of account lockout are set 15min and it will automatically reset. The code implementation are in [login.php](Enhanced/login.php).
      - Disable autocomplete for each input by using ``autocomplete="off"`` 
       
      #### d. Implementing two-factor Authentication (2FA) & Added user authentication pages which are:
      - [registration.php](Enhanced/registration.php), [register.php](Enhanced/register.php)
      -  [index.php](Enhanced/index.php), [login.php](Enhanced/login.php)

     using sonata-project/google-authenticator: Google Authenticator library to generate qr code and provide otp via google authenticator app for authentication.
     The file are: 
      -  [qr.php](Enhanced/qr.php)
      -  [qr_verify.php](Enhanced/qr_verify.php)

      After registration, user will redirecting to [qr.php](Enhanced/qr.php) to scan and verify via their otp number on google authenticator apps. Then, it will redirect user to [index.php](Enhanced/index.php) for login and verify their otp value again.

      #### e. Implementing secure web transmission by using encrypted channel (SSL)
      ![SSL HTTPS](https://github.com/aimaaan/Final-Assessment/assets/99475237/e20dab77-206f-41a5-9ac8-c0354379377c)
        - Implemented SSL (Secure Sockets Layer) by configuring the makecert.bat file to create a new SSL certificate (server.crt) and a private key (server.key). To applied secured localhost website.
     

### 2. Authorisation [aiman]
#### a. Implementing user authorisation by using role-based access control(RBAC) on database level. 
   - roles consist of admin, user, guest
   - each pages will check for role
   - admin able to access admin dashboard pages and can create, read, update and delete user booking form -> [booking_crud.php](Enhanced/booking_crud.php)
   - user able to access all main pages of the flower hotel website including submit the booking form.
   - guest can login without authentication when click login as guest however, guest role is unable to submit the booking form. Only user are given permission to do so.
   - When log in as guest, guest are given by default its session variable which are username, userid and role. As shown code snippet below:

```php
$_SESSION['username'] = 'Guest'; // Assign 'Guest' as the username
$_SESSION['user_id'] = 3;        // Use 0 as the guest user ID
$_SESSION['role'] = 'Guest';     // Set the role to 'Guest'
```
#### b. Implementing secure session management.
   - Implemented on [security_config.php](Enhanced/security_config.php), using ``startSecureSession()``, Ensures setting secure cookie parameters and regenerating the session ID to prevent session fixation attacks.
   - Thus, In order to implemented to other pages, it need to include ``security_config.php`` at the Beginning of Each Page. This will initialize the secure session and set the necessary security headers of that pages. 
     
```php
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        
        ini_set('session.use_only_cookies', 1);
        
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $cookieParams["lifetime"],
            'path' => $cookieParams["path"],
            'domain' => $_SERVER['HTTP_HOST'],  
            'secure' => isset($_SERVER['HTTPS']),  
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        session_start();
        session_regenerate_id(true);  
    }
}
```
#### c. Implementing server side authorization.
   - Checks the role of the user for each page. If the role are permissable then it can access the page. It will always checks the role on the database. For example:
     
```php
// Check if the user is logged in and has the 'User' or 'Admin' role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['User', 'Admin'])) {
    // If not a user or admin, redirect to an unauthorized access page
    echo "<script>alert('You are not authorized to access this page. Please Sign in');</script>";
    exit();
}
```
The session checks are include at each important pages such as:
 - [booking.php](Enhanced/booking.php)
 - [booking_crud.php](Enhanced/booking_crud.php)

#### c. Implementing regenerating session ids on Authentication and logging out
   - using ``startSecureSession()`` function on [security_config.php](Enhanced/security_config.php), it can also regenerate session id for every new login to avoid reuse the same sessionIDs.
   - There are also logout at nav bar to allow user to logout with a single click and invalidate any active session and contents. it uses [logout.php](Enhanced/logout.php) to destroy session everytime user logout.
     
#### d. Implementing httponly flag, secure cookies and destroying invalidated session id
   - Implemented on [security_config.php](Enhanced/security_config.php), set the ``httponly = true;`` to Ensure all cookies, including session cookies, use the HttpOnly flag.
   - Implements secure cookies to ensure that cookies are transmitted securely over HTTPS and are protected against certain types of attacks by configure session and cookies with the ``Secure`` attributes. As shown on the [security_config.php](Enhanced/security_config.php), `` 'secure' => isset($_SERVER['HTTPS'])``. Enable the cookies to only sent to the server over HTTPS connections and prevents the cookie from being transmitted over unencrypted connections, which could be intercepted by attackers.
   - Destroying session Id are also enable on everytime user logged out. using ``session_destroy()`` and ``session_unset();`` in [logout.php](Enhanced/logout.php)

### 3.Input Validation [Nasrullah]
a.Enhanced the [booking.php](Enhanced/booking.php) 
- Using method( Regular Expression Patterns (Regex Patterns) )
  -- We are using Regex because it is a great method to use since it can prevent the SQL injection by limiting the user to key in the data using all characters instead they only can fill up the data using the allowed regex patterns.
  ![image](https://github.com/aimaaan/Final-Assessment/assets/106076684/6d408731-962e-44c8-b5c5-39e3ac1a8cfe)
- Client Side validation
  -- Below is our client-side validation 
  ![image](https://github.com/aimaaan/Final-Assessment/assets/106076684/b99903fa-573f-4324-ac68-601d2773931d)
  -- When the client has fill up the data then they will received the pop up notifications
  ![image](https://github.com/aimaaan/Final-Assessment/assets/106076684/d8809e50-a7e4-4b2d-8b6a-265b643934a5)
### 4. File Security principles [Nasrullah]
a. implement the code that disables the right-click for login and registration pages.

 ```php
<script>
    window.oncontextmenu = function() {
        return false;
    }
</script>
```

b. Adding the robots.txt
- 'robots.txt' is to make sure that web crawlers cannot request from our site.
  
c. Shortened the URL is being implemented by creating .htacces file in htdocs to prevent any URL rewriting which can lead the attackers to make any changes to the folders.
- Doc '.htaccess' is shown below
![image](https://github.com/aimaaan/Final-Assessment/assets/106076684/59a26529-d540-4ee6-9c55-f315f1fd60ec)

### 5. XSS and CSRF Prevention  [zafran]
a. Generate CSRF token and store it in the session when it is not already present.
- generate and validate CSRF tokens for all state-changing requests and ensure the tokens are unique per session and regenerated periodically.
  
![image](https://github.com/aimaaan/Final-Assessment/assets/92367771/1b941526-32bd-4b2a-ae80-109352203db8)

b. Implement a strict CSP policy.
- Content Security Policy (CSP) is a security feature designed to prevent XSS attacks by restricting the resources that can be loaded on a web page.

![image](https://github.com/aimaaan/Final-Assessment/assets/92367771/be13cde8-8ee6-4472-bdc4-10c731eb065f)

### 6. Database Security Principles - prevent SQL injection [zafran]
a. Sanitize all user inputs before processing them. 
- This can be achieved by escaping special characters and filtering input data.

![image](https://github.com/aimaaan/Final-Assessment/assets/92367771/ea83cc59-a445-40b3-ad97-44b3f5304324)
![image](https://github.com/aimaaan/Final-Assessment/assets/92367771/695f4218-10c2-46fc-95df-2cab3e0f4ed4)


### Weekly Progress Report
[Weekly.Progress.Report.-.Google.Docs.pdf](https://github.com/user-attachments/files/15955825/Weekly.Progress.Report.-.Google.Docs.pdf)


## References
1. Webappsec class handouts from our course instructor: [Dr. Muhamad Sadry Abu Seman](https://github.com/muhdsadry), DIS, KICT, IIUM
2. https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers
3. https://infosec.mozilla.org/guidelines/web_security
