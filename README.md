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
- nas
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
     

### 2. Authorisation [aiman]
a. Implementing user authorisation by using role-based access control(RBAC) on database level. 
   - roles consist of admin, user, guest
   - each pages will check for role
   - admin able to access admin dashboard pages and can create, read, update and delete user booking form -> [booking_crud.php](Enhanced/booking_crud.php)
   - user able to access all main pages of the flower hotel website including submit the booking form.
   - guest can login without authentication when click login as guest however, guest role is unable to submit the booking form. Only user are given permission to do so

### 3.Input Validation
a. Enhanced the booking form 
- Implement the regex for the booking for all the input.
- User need to enter all the data required by the form before submitted it follows the regex. 
- When all the inputs are validated, it will go to the database. 

### 4. File Security principles
a. implement the code that enables the right-click for login and registration pages.
- This 
...
<script>
    window.oncontextmenu = function() {
        return false;
    }
</script>
...


### Weekly Progress Report
[Weekly Progress Report - Google Docs.pdf](https://github.com/user-attachments/files/15954439/Weekly.Progress.Report.-.Google.Docs.pdf)

## References
1. Webappsec class handouts from our course instructor: [Dr. Muhamad Sadry Abu Seman](https://github.com/muhdsadry), DIS, KICT, IIUM
