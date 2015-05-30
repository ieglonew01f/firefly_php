     <!-- MAIN SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    {{ HTML::script('public/assets/js/jenkins.main.min.js'); }}
    <!-- EOF MAIN SCRIPTS -->
    <!-- JQUERY PLUGINS -->
    {{ HTML::script('public/assets/js/plugins/jquery.validate.min.js'); }}
    <!-- EOF PLUGINS -->
    <!-- PAGE LEVEL SCRIPTS -->
    @yield('page_js')
    <!-- EOF PAGE LEVEL SCRIPT -->