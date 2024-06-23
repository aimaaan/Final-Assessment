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

1. Added user authentication pages which are: [aiman]
   -  [registration.php](Enhanced/registration.php), [register.php](Enhanced/register.php)
   -  [index.php](Enhanced/index.php), [login.php](Enhanced/login.php)

  using sonata-project/google-authenticator: Google Authenticator library to generate qr code and provide otp via google authenticator app for authentication.
  The file are: 
   -  [qr.php](Enhanced/qr.php)
   -  [qr_verify.php](Enhanced/qr_verify.php)

2. Added user authorisation pages which are: [aiman]
   - 


## References
1. Webappsec class handouts from our course instructor: [Dr. Muhamad Sadry Abu Seman](https://github.com/muhdsadry), DIS, KICT, IIUM
