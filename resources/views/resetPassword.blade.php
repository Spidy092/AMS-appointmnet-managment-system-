<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('public/assets/css/new-login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container{max-width: unset; width: unset}
    </style>
</head>
<body>
    <div class="wrapper">
        <section class="fluid-container">
            <div class="login-container">
                <div class="logo">
                    <span><img src="{{asset('public/assets/images/million-fractional-logo.png')}}" alt="logo"></span>
                </div>
                <div class="login-form">
                    <h2>Reset Password</h2>
                    <form method="POST" action="{{ url('resetPasswordFunc')}}">
                        @csrf
                        <div class="inputBox">
                            <span><i class="fa-solid fa-lock"></i></span>
                            <input  type="password" id="password" onkeyup="this.setAttribute('value', this.value);"  name="password" maxlength="60" value="{{old('password')}}" required>
                            <label for="password">Password</label>
                            <span class="password-eye" onclick="togglePassword('password', 'toggle-icon')"><i id = "toggle-icon" class="fa-solid fa-eye"></i></span>
                            <ul class="helper-text" id="errors-list">
                                <li class="length">Must be at least 8 characters long.</li>
                                <li class="lowercase">Must contain a lowercase letter.</li>
                                <li class="uppercase">Must contain an uppercase letter.</li>
                                <li class="number">Must contain a number.</li>
                                <li class="special">Must contain a special character.</li>
                            </ul>
                            <span class="text-danger">
                                @error('password')
                                    * {{$message}}
                                @enderror
                            </span>
                        </div>
                        <input class="form-control" type="hidden" name="token" value="{{$token}}" maxlength="60">
                        <div class="inputBox">
                            <span><i class="fa-solid fa-lock"></i></span>
                            <input  type="password" id="confirmpassword" onkeyup="this.setAttribute('value', this.value);"  name="password_confirmation" maxlength="60" value="{{old('password_confirmation')}}" required>
                            <label for="confirmpassword">confirm Password</label>
                            <span class="password-eye" onclick="togglePassword('confirmpassword', 'confirm-toggle-icon')"><i id = "confirm-toggle-icon" class="fa-solid fa-eye"></i></span>
                            <span class="text-danger">
                                @error('password_confirmation')
                                    * {{$message}}
                                @enderror
                            </span>
                        </div>
                          {{-- <div class="forgot">
                            <a href="{{ url('forgotPassword')}}">Forgot Password?</a>
                          </div>
                          <div class="login-btn">
                            <button name="txtsubmit">Reset Password</button>
                        </div> --}}

                        <div class="login-btn send-mail-btn">
                            <a href="{{ route('login')}}">Sign in</a>
                            <button name="txtsubmit">Reset</button>
                        </div>

                    </form>
                  </div>
            </div>
        </section>
    </div>



        @if(session('error'))
            <div class="alert alert-danger" id="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" id="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
        @endif


    {{-- <script src="{{ asset('resources/js/jquery.js') }}"></script>
    <script src="{{ asset('resources/js/common.js') }}"></script> --}}
    <script>
        function togglePassword(passwordId, iconId) {
            var passwordField = document.getElementById(passwordId);
            var toggleIcon = document.getElementById(iconId);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    <script src="{{ asset('public/assets/js/passwordValidation.js') }}"></script>
</body>
</html>
