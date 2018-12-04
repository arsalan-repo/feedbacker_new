<head>
    <title>Feedbacker</title>
    <link rel="shortcut icon" type="image/jpg" href="pic/Favicon.jpg"/>


    <!-- metas -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--/ metas -->

    <!--jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

    <!-- angular js -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <!-- angular js animation library-->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-animate.js"></script>
    <script src="/js/lib/ui-bootstrap.js"></script>

    <!-- owl carouse-->
    <link rel="stylesheet" href="/css/lib/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/lib/owl.theme.default.min.css">
    <!-- owl carouse-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <!-- animate css lib -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <!-- animate css lib -->

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.5/angular-material.min.css" />
    <link rel="stylesheet" href="/js/jk-rating-stars.min.css" />
    <link rel="stylesheet" href="/css/style.css" />






    <!--custom style-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('style')
    <!--/ styles -->




</head>
