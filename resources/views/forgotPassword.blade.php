<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('public/assets/css/new-login.css') }}">
    <title>Million Fractional Forget password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="wrapper forget-pass-page">
        <section class="fluid-container">
            <div class="login-container">
                <div class="logo">
                    <span><img src="{{asset('public/assets/images/million-fractional-logo.png')}}" alt="logo"></span>
                </div>
                <div class="login-form">
                    <h2>Your email</h2>
                    <form  id="send" method="POST" action="{{ url('forgot-password-send-email')}}">
                        @csrf
                        <div class="inputBox">
                          <span><i class="fa-regular fa-envelope"></i> </span>
                          <input type="email" onkeyup="this.setAttribute('value', this.value);"  name="txtEmail" maxlength="60" value="{{old('txtEmail')}}" required>
                          <label>E-mail ID</label>
                          <span class="text-danger">
                              @error('txtEmail')
                              * {{$message}}
                              @enderror
                          </span>
                        </div>
                        <div class="login-btn send-mail-btn">
                            <a href="{{ route('login')}}">Sign in</a>
                            <button name="txtsubmit">Next</button>
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

</body>
</html>
