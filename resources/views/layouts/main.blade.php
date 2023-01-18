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
<!-- Toastr style -->
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
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

<!-- Modal -->
<div class="modal fade" id="changePasswordModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <b class="modal-title" id="changePasswordModalLabel">{{ multi_lang('change_password') }}</b>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="formChangePassword">
                @csrf
                <div class="form-group row">
                    <div class="col-12" id="msg-change-password">

                    </div>
                </div>
                <div class="form-group row">
                    <label for="old_password" class="col-sm-5 col-form-label font-weight-normal">{{ multi_lang('old_password') }}</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" id="old_password" name="old_password">
                        <div class="invalid-feedback" id="msg_old_password"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="new_password" class="col-sm-5 col-form-label font-weight-normal">{{ multi_lang('new_password') }}</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <div class="invalid-feedback" id="msg_new_password"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="re_new_password" class="col-sm-5 col-form-label font-weight-normal">{{ multi_lang('re_new_password') }}</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" id="re_new_password" name="re_new_password">
                        <div class="invalid-feedback" id="msg_re_new_password"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ multi_lang('cancel') }}</button>
          <button type="button" id="btnSaveChangePassword" onclick="change_password()" class="btn btn-primary">{{ multi_lang('save') }}</button>
        </div>
      </div>
    </div>
  </div>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<!-- Custome -->
<script src="{{ asset('assets/dist/js/custome.js') }}"></script>
<script>
    function open_change_password(){
        $('#formChangePassword')[0].reset();
        $("#msg-change-password").html("");

        $("#old_password").removeClass('is-invalid');
        $("#msg_old_password").removeClass('d-block');

        $("#new_password").removeClass('is-invalid');
        $("#msg_new_password").removeClass('d-block');

        $("#re_new_password").removeClass('is-invalid');
        $("#msg_re_new_password").removeClass('d-block');

        $('#btnSaveChangePassword').text("{{ multi_lang('save') }}"); //change button text
        $('#btnSaveChangePassword').attr('disabled',false); //set button disable
    }

    function change_password(){
        $("#msg-change-password").html("");

        $("#old_password").removeClass('is-invalid');
        $("#msg_old_password").removeClass('d-block');

        $("#new_password").removeClass('is-invalid');
        $("#msg_new_password").removeClass('d-block');

        $("#re_new_password").removeClass('is-invalid');
        $("#msg_re_new_password").removeClass('d-block');

        $('#btnSaveChangePassword').text("{{ multi_lang('process') }}..."); //change button text
        $('#btnSaveChangePassword').attr('disabled',true); //set button disable

        $.ajax({
            type: "POST",
            url: 'change-password',
            data: $("#formChangePassword").serialize(),
        }).done(function (response) {

            if(response.status && response.status_item){
                toastr.success(response.message);
                $('#changePasswordModal').modal('toggle');
            }else{
                if(response.message != ""){
                    $("#msg-change-password").html(response.message);
                }

                if(response.message_item.old_password !== undefined && response.message_item.old_password != ""){
                    $("#old_password").html(response.message_item.old_password).addClass('is-invalid');
                    $("#msg_old_password").html(response.message_item.old_password).addClass('d-block');
                }

                if(response.message_item.new_password !== undefined && response.message_item.new_password != ""){
                    $("#new_password").html(response.message_item.new_password).addClass('is-invalid');
                    $("#msg_new_password").html(response.message_item.new_password).addClass('d-block');
                }

                if(response.message_item.re_new_password !== undefined && response.message_item.re_new_password != ""){
                    $("#re_new_password").html(response.message_item.re_new_password).addClass('is-invalid');
                    $("#msg_re_new_password").html(response.message_item.re_new_password).addClass('d-block');
                }
            }

            $('#btnSaveChangePassword').text("{{ multi_lang('save') }}"); //change button text
            $('#btnSaveChangePassword').attr('disabled',false); //set button disabl

        }).fail( function (jqXHR, exception) {

            let msg = jquery_ajax_error(jqXHR, exception)
            toastr.error(msg);

            $('#btnSaveChangePassword').text("{{ multi_lang('save') }}"); //change button text
            $('#btnSaveChangePassword').attr('disabled',false); //set button disable

        });
    }
</script>
</body>
</html>


