
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Credentials</title>
    <style>
        /* email css */
        .reset { padding: 5px 8px; background-color: #3799bc !important; color: #fff !important; display: inline-block; margin: auto; text-decoration: none;}
        .text-c{width: 100%; text-align: center}
        p{color: #000 !important}
        .email-wrapper{width: 100%; background-color: #f5f5f5}
        .content-wrapper{margin: auto; padding: 25px 15px; border-top: 5px solid #3799bc;  width: 100% ; max-width: 600px; background-color: #fff }
        .logo{width:100%; text-align: center;}
        .logo span{display: flex; max-width: 50px; width: 100%; max-width:50px; margin: 0 auto 10px;}
        .logo img{width: 100%;}
        .logo h4{color: #102d82 !important; font-family: content-medium; margin: 0 !important; padding:0 !important }
        /* email css */
    </style>

</head>
<body>
    <div class="email-wrapper">
        <div class="content-wrapper">
            <div class="logo text-c">
                    <h4>Million Fractional</h4>
            </div>
            <div class="email-content">
                <p>Dear {{$name}},</p>

                <div>
                    <p>Welcome to Million Fractional!</p>

                    <p>Your account has been created successfully. Below is your credentials:</p>
                    <p><strong>Username: {{ $username }}</strong></p>
                    <p><strong>Password: {{ $password }}</strong></p>

                    <p>Please change your password after logging in for the first time.</p>
                </div>

                <p>Regards,<br>
                    Million Fractional <br>
                <a href="{{$websitePrefix}}">{{$websitePrefix}}</a><br>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

