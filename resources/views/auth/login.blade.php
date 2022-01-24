@extends('layouts.app')

@section('content')
    <div class="custom-layout__login container-fluid" >
        <div class="container">
            <div class="d-flex justify-content-center align-items-center flex-column">
              
                <img src="{{ asset('image/login_text.png') }}" class="mb-2"  alt="LOGO">
                <div class="custom-card__glass card d-flex justify-content-center align-items-center p-4 card"
                    style="max-width: 90%; width: 600px border:1px solid black;">
                    <div class="card-body container-fluid px-5 py-3">
                        <h3 class="text-center text-black">ACCOUNT LOGIN</h3>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email"
                                    class="form-label  custom-input__label">{{ __('E-Mail Address') }}</label>
                                <div class="d-flex flex-column">
                                    <input id="email" type="email"
                                        class="text-black form-control custom-input @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <label for="password" class="col-sm-12 control-label">{{ __('Password') }}</label>
                                <input type="password"
                                        id="password"
                                        class="form-control custom-input @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="current-password"
                                >
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2" onclick="show()"><i class="fa fa-eye" id="icons" aria-hidden="true"></i></span>
                                </div>
                            </div>
                           
                            @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                            @endif

                            <div class="d-grid col-6 float-right ">
                                <button class="btn btn-block btn-primary mt-4 py-3 text-uppercase text-white"
                                    type="submit">
                                    {{ __('SIGN IN') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function show() {
        var x = document.getElementById("password");
        var b = document.getElementById('icons');
        console.log( b.classList.value );
        if (x.type === "password") {
            x.type = "text";
            b.classList.value = 'fa fa-eye-slash';
       
        } else {
            x.type = "password";
            b.classList.value = 'fa fa-eye';
        }
}
</script>