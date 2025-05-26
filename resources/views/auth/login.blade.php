<div class="login-wrapper">
    <div class="login-box">
        <img src="{{ asset('icon/icongym.png') }}" alt="Logo" class="login-logo">

        <div class="login-card">
            <div class="login-title">{{ __('Login') }}</div>

            <div class="login-content">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login-input-group">
                        <label for="email" class="login-input-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="login-input-field @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="login-error-message" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="login-input-group">
                        <label for="password" class="login-input-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="login-input-field @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="login-error-message" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="login-input-group">
                        <div class="login-remember-group">
                            <input class="login-remember-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="login-input-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="login-input-group">
                        <button type="submit" class="login-submit-btn">
                            {{ __('Login') }}
                        </button>

                        {{-- @if (Route::has('password.request')) --}}
                        {{--     <a class="login-forgot-link" href="{{ route('password.request') }}"> --}}
                        {{--         {{ __('Forgot Your Password?') }} --}}
                        {{--     </a> --}}
                        {{-- @endif --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/login.css') }}">
