<!-- header-->
<div class="" id = "navbarApp"  ng-controller="navbarController" ng-init="determineWebsiteLanguage();">
    <div class="container-fluid" ng-cloak>
        <div class="row top-nav-container py-2">

            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 text-right">
                <img src="/images/logo.png" class="img-fluid logo-image">
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-4 col-xl-4   text-center logo-text pl-0 pt-4">
                <h3 class="header-text mt-0">@lang('home.header.website-name-header')</h3>
                <h5 class="lead">
                    <span class="sub-header-text  ml-md-5 ml-lg-0"> <a
                                href="">@lang('home.header.website-name-subheader')</a></span>
                </h5>
            </div>

            <div class="text-right col-sm-4 col-md-2 col-lg-2 col-xl-2 offset-sm-4 offset-lg-3 offset-md-3 offset-xl-3 top-nav-countries px-md-0 pt-3">


                <select class="form-control select" ng-model="lang" ng-change="changeLanguage(lang)"
                        ng-init="">

                    <option class="test-flag" value="ar"> @{{ languages[languages.default].ar }} <span
                                class="flag-icon flag-icon-jo"></span></option>
                    <option value="en"> @{{ languages[languages.default].en }} <span
                                class="flag-icon flag-icon-gb"></span></option>


                </select>

            </div>
        </div>
    </div>

    <div class="container" ng-cloak>
        <div class="row">
            <div class="col-12 px-0 px-md-1">
                <div class="" id="navFixed">
                    <nav class="navbar navbar-expand-lg navbar-light  rtl">

                        <button class="navbar-toggler col-6 show-login-icon" ng-click="toggleLogin()">
                            <span class="login-icon">Login</span> <i class="fas fa-sign-in-alt ml-1"></i>
                        </button>

                        <button class="navbar-toggler col-6 " type="button" data-toggle="collapse"
                                data-target="#navbarTogglerDemo02"
                                aria-controls="navbarTogglerDemo02" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <i class="fas fa-bars navbar-bars"></i>
                        </button>

                        <div class="collapse navbar-collapse mr-0 mr-md-0" id="navbarTogglerDemo02">
                            <ul class="navbar-nav ml-auto mr-3 mr-md-0">
                                <li class="nav-item active">

                                    <a class="nav-link px-3" href="/">
                                        @lang('home.navbar.home')

                                        <span class="text-center animate-star star">
                                <img src="/images/star.png">

                            </span>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3" href="/about-us"> @lang('home.navbar.about-us')
                                        <span class="text-center star">
                              <img src="/images/star.png">
                            </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled px-3" href="#"> @lang('home.navbar.medical-guide')
                                        <span class="text-center star">
                              <img src="/images/star.png">
                            </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled px-3" href="/services">
                                        @lang('home.navbar.services')
                                        <span class="text-center star">
                                <img src="/images/star.png">
                            </span>
                                    </a>
                                </li>

                                @if(!Auth::check())
                                    <li class="nav-item">
                                        <a class="nav-link disabled px-3"
                                           href="/register"> @lang('home.navbar.membership')
                                            <span class="text-center star">
                             <img src="/images/star.png">
                            </span>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link disabled px-3" href="#"> @lang('home.navbar.news')
                                        <span class="text-center star">
                                <img src="/images/star.png">
                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link disabled px-3" href="#"> @lang('home.navbar.contact-us')
                                        <span class="text-center star">
                              <img src="/images/star.png">
                            </span>
                                    </a>
                                </li>


                            </ul>
                        </div>

                    </nav>

                    <!-- navbar-->


                </div>
            </div>
        </div>
    </div>

    <!-- drop down login form -->
    <div class="" ng-cloak>
        @if(Auth::guest())
            <div class="row  login" id="find-doctor" style="background: rgba(41, 83, 119, 0.69)"
                 ng-show="showLogin">

                <div class="col-8  m-auto my-2 rtl">


                    <form class="my-2" method="post" action="login">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-12 col-md-4 mb-sm-2">
                                <input type="text" class="form-control" ng-model="credentials.user_name"
                                       name="user_name" placeholder="@lang('home.login-form.placeholders.username')">
                            </div>
                            <div class="col-12 col-md-4  mb-sm-2">
                                <input type="password" class="form-control" ng-model="credentials.password"
                                       name="password" placeholder="@lang('home.login-form.placeholders.password')">
                            </div>
                            <div class="col-12 col-md-4  mb-sm-2">
                                <button type="submit"
                                        class="btn btn-default w-100 login-button">@lang('home.login-form.inner-login-button')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif


        <button ng-click="toggleLogin()" class="show-login-btn btn btn-default">
            @if (!Auth::guest())
                {{Auth::user()->firstName . "  " .Auth::user()->lastName}}
            @else

                @lang('home.login-form.outer-login-button')
            @endif
        </button>


    </div>
    <!-- drop down login form-->
</div>
<!-- header-->










