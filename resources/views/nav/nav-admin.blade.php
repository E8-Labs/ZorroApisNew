<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                           {{--  <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            {{--  <ul id="tb_options" class="navbar-nav mr-auto">
                                    <li id="menu_contest_tb" class="nav-item menu_item">
                                        <a  class="nav-link "  href="{{ route('home') }}" style="  " >
                                        {{ __('Contest') }}</a>
                                    </li>
                                     <li id="menu_settings_tb" class="nav-item menu_item">
                                        <a class="nav-link" href="{{ route('settings') }}" > 
                                        {{ __('Settings') }}</a>
                                    </li>
                                    <li id="menu_how_to_play_tb" class="nav-item menu_item">
                                        <a  class="nav-link " href="{{ route('how_to_play') }}" >
                                        {{ __('How To Play') }}</a> 
                                    </li>
                                    <li id="menu_faq_tb" class="nav-item menu_item">
                                        <a  class="nav-link " href="{{ route('faq') }}" >
                                        {{ __('FAQ') }}</a> 
                                    </li>
                                    <li id="menu_term_of_use_tb" class="nav-item menu_item">
                                        <a  class="nav-link " href="{{ route('term_of_use') }}" >
                                        {{ __('Terms of use') }}</a> 
                                    </li>
                                    <li id="menu_privacy_policy_tb" class="nav-item menu_item">
                                        <a  class="nav-link " href="{{ route('privacy_policy') }}" >
                                        {{ __('Privacy policy') }}</a> 
                                    </li>

                                     <li id="menu_users_tb" class="nav-item menu_item">
                                        <a  class="nav-link " href="{{ route('users') }}" >
                                        {{ __('Users') }}</a> 
                                    </li>
                          </ul> --}}
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>