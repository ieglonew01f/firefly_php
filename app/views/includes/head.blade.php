    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jenkins - @yield('title')</title>

    <!-- MAIN CSS -->
    {{ HTML::style('public/assets/css/jenkins.main.min.css'); }}
    <!-- FONT AWESOME AND OTHER CSS-->
    {{ HTML::style('public/assets/css/font-awesome.min.css'); }}
    {{ HTML::style('public/assets/css/checkboxes.css'); }}
    {{ HTML::style('public/assets/css/lineicons.css'); }}
    {{ HTML::style('public/assets/css/jquery-confirm.min.css'); }}
    {{ HTML::style('public/assets/css/jenkins.global.css'); }}
    <!-- PAGE LEVEL CSS -->
    @yield('page_css')
    <!-- EOF PAGE LEVEL CSS -->
