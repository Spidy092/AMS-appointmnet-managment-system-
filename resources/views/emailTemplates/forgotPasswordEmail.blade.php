<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
                    <p>You are receiving this email because we received a password reset request for your account.</p>

                    <p>To reset your password, click on the following link:</p>

                    <div class="text-c">
                        <a class="reset" href="{{$applicationPrefix}}resetPassword/{{$token}}">Reset Password</a>
                    </div>

                    <p>If you did not request a password reset, no further action is required.</p>
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
