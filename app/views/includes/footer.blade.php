     <!-- MAIN SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    {{ HTML::script('public/assets/js/base/jenkins.main.min.js'); }}
    <!-- EOF MAIN SCRIPTS -->

    <!-- JQUERY PLUGINS -->
    {{ HTML::script('public/assets/js/plugins/jqueryTypeahead/typeahead.bundle.min.js'); }}
    {{ HTML::script('public/assets/js/plugins/JqueryIonSound/ion.sound.min.js'); }}
    {{ HTML::script('public/assets/js/polling/jenkins.poller.js'); }}
    {{ HTML::script('public/assets/js/plugins/jquery.validate.min.js'); }}
    {{ HTML::script('public/assets/js/plugins/jquery.slimscroll.min.js'); }}
    {{ HTML::script('public/assets/js/plugins/jquery.grid-a-licious.min.js'); }}
    {{ HTML::script('public/assets/js/plugins/jqueryImageSlider/galleria-1.4.2.min.js'); }}
    {{ HTML::script('public/assets/js/plugins/jqueryConfirm/jquery-confirm.min.js'); }}
    {{ HTML::script('public/assets/js/plugins/socket.io/socket.io.min.js')}}
    {{ HTML::script('public/assets/js/comet/jenkins.comet.js')}}
    {{ HTML::script('public/assets/js/notifications/jenkins.notifications.js')}}
    {{ HTML::script('public/assets/js/people/jenkins.people.js'); }}
    <!-- EOF PLUGINS -->

    <!-- PAGE LEVEL SCRIPTS -->
    @yield('page_js')
    <!-- EOF PAGE LEVEL SCRIPT -->
