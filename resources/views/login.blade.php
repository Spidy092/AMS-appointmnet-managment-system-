
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('public/assets/css/new-login.css') }}">
    <title>Million Fractional Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <section class="fluid-container">
            <div class="login-container">
                <div class="logo">
                    <span><img src="{{asset('public/assets/images/million-fractional-logo.png')}}" alt="logo"></span>
                </div>
                <div class="login-form">
                    <h2>Login</h2>
                    <form method="POST" action="{{ url('loginUser')}}">
                        @csrf
                      <div class="inputBox">
                        <span><i class="fa-regular fa-envelope"></i> </span>
                        <input type="email" onkeyup="this.setAttribute('value', this.value);"  name="loginName" maxlength="60" value="{{old('loginName')}}" required>
                        <label>E-mail ID</label>
                        <span class="text-danger">
                            @error('loginName')
                            * {{$message}}
                            @enderror
                        </span>
                      </div>
                      <div class="inputBox">
                            <span><i class="fa-solid fa-lock"></i></span>
                            <input  type="password" id="password" onkeyup="this.setAttribute('value', this.value);"  name="loginPassword" maxlength="60" value="{{old('loginPassword')}}" required>
                            <label for="password">Password</label>
                            <span class="password-eye" onclick="togglePassword()"><i id = "toggle-icon" class="fa-solid fa-eye"></i></span>

                            <span class="text-danger">
                                @error('loginPassword')
                                    * {{$message}}
                                @enderror
                            </span>
                          </div>
                          <div class="forgot">
                            <a href="{{ url('forgotPassword')}}">Forgot Password?</a>
                          </div>
                          <div class="login-btn">
                            <button name="txtsubmit">Sign in</button>
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
    {{-- @if ($errors->any())
        <div class=" ">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('public/assets/js/jquery.js') }}"></script> --}}

    <script>
    /* ************************ Alert ************************ */
        // $(document).ready(function() {
        //     $('.close').on('click', function () {
        //         $(this).parent().hide();
        //     });

        //     setTimeout(function() {
        //         $('.alert').fadeOut('slow');
        //     }, 5000); // 5 seconds
        // });

    /* ************************ Toggle Password Visibility ************************ */
    function togglePassword() {
            var passwordField = document.getElementById('password');
            var toggleIcon = document.getElementById('toggle-icon');
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
    {{-- <script type="text/javascript">
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script> --}}
</body>
</html>
