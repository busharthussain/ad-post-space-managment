<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Sharepeeps</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/assets/bootstrap/css/bootstrap.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('/assets/css/style.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('/assets/css/custom.css') !!}"/>
    {!! Charts::styles() !!}
</head>

<body>
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
@yield('content')
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->

{!! HTML::script('assets/js/jquery.min.js') !!}
<!-- Bootstrap tether Core JavaScript -->
{!! HTML::script('assets/js/popper.min.js') !!}
{!! HTML::script('assets/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('assets/js/custom.min.js') !!}
<!--Custom JavaScript -->
</body>

</html>
