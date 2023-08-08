@extends('system.layouts.masterGuest')
@section('content')

    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="login-card">

                    <form class="theme-form login-form" method="post" action="{{ route('login') }}">
                        @include('system.partials.message')
                        <h4>{{translate('Login')}}</h4>
                        <h6>{{translate('Welcome back! Log in to your account.')}}</h6>
                        @csrf
                        <div class="form-group">
                            <label>{{translate('Email Address')}}</label>
                            <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                                <input class="form-control" type="text" name="email" placeholder="Email Address"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{translate('Password')}}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="icon-lock"></i>
                                </span>
                                <input class="form-control" type="password" name="password" required="" id="password"
                                       placeholder="*********">
                            </div>
                        </div>
                        <h6>{{ translate('You are current using IP') }} - <strong>{{ Request::ip() }}</strong></h6>

                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">{{translate('Remember password')}}</label>
                            </div>
                            <a class="link" href="{{ route('forgot.password') }}">{{translate('Forgot password?')}}</a>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">{{translate('Sign in')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#password').on("input", function () {
                const value = $(this).val();
                // Check if the password input value is more than 0
                if (value.length > 0) {
                    // If the value is more than 0, append the eye icon
                    if ($("#togglePassword").length === 0) {
                        $(this).after('<span class="input-group-text" id="togglePassword" style="cursor: pointer;"><i class="fa fa-eye"></i></span>');
                    }
                } else {
                    // Otherwise, remove the eye icon if it exists
                    $("#togglePassword").remove();
                }
            });

            // Toggle password visibility when clicking on the eye icon
            $(document).on("click", "#togglePassword", function () {
                var passwordInput = $("#password");
                var type = passwordInput.attr("type");
                passwordInput.attr("type", type === "password" ? "text" : "password");

                var eyeIcon = $(this).find("i");
                console.log(eyeIcon);
                eyeIcon.toggleClass("fa-eye fa-eye-slash");
            });

        });
    </script>
@endsection
