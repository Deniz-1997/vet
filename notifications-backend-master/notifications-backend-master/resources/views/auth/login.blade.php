@extends('layouts.app')

@section('content')
    <div class="autorize-wr">
        <div class="autorize">
            <div class="autorize__logo"><img src="{{ url('/img/logo.svg') }}" alt=""></div>
            <div class="autorize__header">
                Комплексное облачное решение <br>для ветеринарии
            </div>
            <div class="autorize__form">
                <div class="autorize__form-h">Авторизация</div>
                <form action="" post="">
                    <div class="autorize__form-row">
                        <input type="text" class="autorize__form-inp" placeholder="Почта или Телефон">
                    </div>
                    <div class="autorize__form-row">
                        <input type="password" class="autorize__form-inp" placeholder="Пароль">
                    </div>
                    <div class="autorize__form-btn"><input type="submit" value="ВОЙТИ"></div>
                    <div class="autorize__form-check">
                        <input id="autorize-save" type="checkbox">
                        <label for="autorize-save">Не выходить из аккаунта</label>
                    </div>
                    <div class="autorize__form-footer"><a href="">Забыли пароль?</a></div>
                </form>
            </div>
        </div>
    </div>

    <div class="autorize-footer">
        <div class="autorize-footer__head">Служба поддержки</div>
        <div class="autorize-footer__phone"><a href="tel:88005552404">8 800 555 24 04</a></div>
        <div class="autorize-footer__mob"><a href="mailto:help@corvet.ru">help@corvet.ru</a></div>
    </div>

{{--    <div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Login') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    <form method="POST" action="{{ route('login') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">--}}

{{--                                @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                    <label class="form-check-label" for="remember">--}}
{{--                                        {{ __('Remember Me') }}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-8 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Login') }}--}}
{{--                                </button>--}}

{{--                                @if (Route::has('password.request'))--}}
{{--                                    <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                        {{ __('Forgot Your Password?') }}--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection
