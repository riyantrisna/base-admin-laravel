<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ubiz {{ $title }}</title>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>
<body class="layout-fixed layout-navbar-fixed sidebar-mini layout-footer-fixed">
<div class="wrapper">

	@include('partials.navbar')
	@include('partials.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@yield('container')
</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
<footer class="main-footer">
	<!-- To the right -->
	<div class="float-right d-none d-sm-inline">
	</div>
	<!-- Default to the left -->
	<strong>Copyright &copy; {{ date('Y') }} Riyan Systemify.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->


