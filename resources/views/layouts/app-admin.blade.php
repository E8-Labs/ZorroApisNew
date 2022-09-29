<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>

 

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js" ></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"  ></script>


    

       <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js" ></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js" ></script>



{{-- jquery-3.2.1.js --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
     {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet"> --}}
     {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet"> --}}
     <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">







     {{-- <script src="{{ asset('js/croppie.js') }}" defer></script>   --}}
    {{-- <link href="{{ asset('css/croppie.css') }}" rel="stylesheet">  --}}

{{--     <link rel="stylesheet" type="text/css" 
 href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.css">
 <script 
 src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"> 
</script> --}}

</head>
<body>
    <div id="app">
        <div class="container"> 
       
       @include('nav.nav-admin')

        <main class="py-4">
             <div class="row col-lg-12">

                 <div class="col-lg-2" id="side_menu"   >
                   <ul class="navbar-nav ml-auto">
                             <li id="menu_contest" class="nav-item menu_item">
                                <a  class="nav-link "  href="{{ route('home') }}" style="  " >
                                {{ __('Dashboard') }}</a>
                            </li>
                             <li id="menu_renders" class="nav-item menu_item">
                                <a  class="nav-link "  href="{{ route('lenders') }}" style="  " >
                                {{ __('Lenders') }}</a>
                            </li>
                            {{-- 
                             <li id="menu_settings" class="nav-item menu_item">
                                <a class="nav-link" href="{{ route('settings') }}" > 
                                {{ __('Settings') }}</a>
                            </li>
                            <li id="menu_how_to_play" class="nav-item menu_item">
                                <a  class="nav-link " href="{{ route('how_to_play') }}" >
                                {{ __('How To Play') }}</a> 
                            </li>
                            <li id="menu_faq" class="nav-item menu_item">
                                <a  class="nav-link " href="{{ route('faq') }}" >
                                {{ __('FAQ') }}</a> 
                            </li>
                            <li id="menu_term_of_use" class="nav-item menu_item">
                                <a  class="nav-link " href="{{ route('term_of_use') }}" >
                                {{ __('Terms of use') }}</a> 
                            </li>
                            <li id="menu_privacy_policy" class="nav-item menu_item">
                                <a  class="nav-link " href="{{ route('privacy_policy') }}" >
                                {{ __('Privacy policy') }}</a> 
                            </li>
                             <li id="menu_users" class="nav-item menu_item">
                                <a  class="nav-link " href="{{ route('users') }}" >
                                {{ __('Users') }}</a> 
                            </li> --}}
                    </ul>
                </div>

                <div class="col-lg-10" >
                    @yield('content')
                   {{--  <div class="card">
                        <div class="card-header">Dashboard</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            You are logged in!
                        </div>
                    </div> --}}
                </div>

            </div>
            

        </main>
        {{-- @include('footer.footer') --}}
    </div>

    </div>

    <script>
      //  $('textarea').ckeditor();
        // $('.textarea').ckeditor(); // if class is prefered.
    </script>

   {{--   <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'article-ckeditor' );
    </script> --}}


</body>
 @yield('pagejsfile')
</html>