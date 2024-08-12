<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ translate($role) }} | {{ translate('login')}}</title>
    <link rel="shortcut icon" href="{{getStorageImages(path: getWebConfig(name: 'company_fav_icon'), type:'backend-logo')}}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/icon-set/style.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/theme.minc619.css?v=1.0') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/style.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/toastr.css') }}">
    <style>
          .thin-font {
        font-family: 'Lato', sans-serif; /* Use the font family you included */
        font-weight: 300; /* Thin weight */
    }
        /* CSS for positioning the logo in the top-left corner */
        .logo-container {
            position: absolute; /* Or fixed if you want it to stay in place when scrolling */
            top: 0;
            left: 0;
            padding: 10px; /* Optional: add padding to adjust spacing */
            z-index: 1000; /* Ensure the logo is above other content */
        }

        .logo {
            height: 40px; /* Adjust height as needed */
        }
    </style>
</head>

<body>
<main id="content" role="main" class="main">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero __inline-1">
        <figure class="position-absolute right-0 bottom-0 left-0">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
                <polygon fill="#fff" points="0,273 1921,273 1921,0 "/>
            </svg>
        </figure>
    </div>
    <div class="container py-5 py-sm-7">
        {{-- <label class="badge badge-soft-success float-right __inline-2">{{translate('software_version')}}
            : {{ env('SOFTWARE_VERSION') }}</label> --}}
            @php($companyWebLogo = getWebConfig(name: 'company_web_logo'))
        <a class="logo-container" href="javascript:">
            <img class="logo" src="{{ getStorageImages(path: $companyWebLogo, type: 'backend-logo') }}" alt="Company Logo">
        </a>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card card-lg mb-5">
                    <div class="card-body">
                        <form id="form-id" action="{{route('login')}}" method="post" id="admin-login-form">
                            @csrf
                            <div class="text-center">
                                <div class="mb-5">
                                    <h1 class="display-4  thin-font">Welcome Back ! </h1><br>
                                </div>
                            </div>

                            <input type="hidden" class="form-control mb-3" name="role" id="role" value="{{ $role }}">

                            <div class="js-form-message form-group">

                                <input type="email" class="form-control form-control-lg" name="email" id="signingAdminEmail"
                                       tabindex="1" placeholder="example@email.com" aria-label="email@address.com"
                                       required data-msg="Please enter a valid email address.">
                            </div>
                            <div class="js-form-message form-group">
                            

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                           name="password" id="signingAdminPassword"
                                           placeholder="{{ translate('8+_characters_required') }}"
                                           aria-label="8+ characters required" required
                                           data-msg="Your password is invalid. Please try again."
                                           data-hs-toggle-password-options='{
                                                     "target": "#changePassTarget",
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": "#changePassIcon"
                                            }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                           name="remember">
                                    <label class="custom-control-label text-muted" for="termsCheckbox">
                                        {{translate('remember_me')}}
                                    </label>
                                </div>
                            </div>
                            {{-- @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                <div id="recaptcha_element" class="w-100;" data-type="image"></div>
                                <br/>
                            @else
                                <div class="row p-2">
                                    <div class="col-6 pr-0">
                                        <input type="text" class="form-control form-control-lg border-0"
                                               name="default_captcha_value" value="" required
                                               placeholder="{{translate('enter_captcha_value')}}" autocomplete="off">
                                    </div>
                                    <div class="col-6 input-icons bg-white rounded">
                                        <a class="get-login-recaptcha-verify" data-link="{{ URL('login/recaptcha/') }}">
                                            <img src="{{ URL('login/recaptcha/'.rand().'?captcha_session_id=default_recaptcha_id_'.$role.'_login') }}"
                                                 class="input-field w-90 h-75" id="default_recaptcha_id" alt="">
                                            <i class="tio-refresh icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif --}}

                            <button type="submit" class="btn btn-lg btn-block btn--primary">
                                {{ translate('sign_in')}}
                            </button>
                        </form>
                    </div>
                    @if(env('APP_MODE')=='demo')
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-10">
                                    <span id="admin-email" data-email="{{ \App\Enums\DemoConstant::ADMIN['email'] }}">{{translate('email')}} : {{ \App\Enums\DemoConstant::ADMIN['email'] }}</span><br>
                                    <span id="admin-password" data-password="{{ \App\Enums\DemoConstant::ADMIN['password'] }}">{{translate('password')}} : {{ \App\Enums\DemoConstant::ADMIN['password'] }}</span>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn--primary" id="copyLoginInfo"><i class="tio-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<span id="message-please-check-recaptcha" data-text="{{ translate('please_check_the_recaptcha') }}"></span>
<span id="message-copied_success" data-text="{{ translate('copied_successfully') }}"></span>

<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor.min.js')}}"></script>

<script src="{{dynamicAsset(path: 'public/assets/back-end/js/theme.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/toastr.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/login.js')}}"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        "use strict";
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        "use strict";
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endif

</body>
</html>

