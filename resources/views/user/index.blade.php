@extends('layouts.main')
@section('container')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		<div class="col-sm-12">
			<ol class="breadcrumb float-sm-left">
			    <li class="breadcrumb-item active">{{ multi_lang('user') }}</li>
			</ol>
		</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
	<div class="container-fluid">
		<div class="row">
            <!-- DataTable with Hover -->
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="table-responsive p-3">
                        <button class="btn btn-success mb-3" onclick="add()"><i class="fas fa-plus mr-2"></i> Add</button>
                        <table class="table table-bordered table-hover dataTable dtr-inline" id="dataTable" aria-describedby="example2_info">
                            <thead>
                                <tr>
                                    <th>{{ multi_lang('number') }}</th>
                                    <th>{{ multi_lang('action') }}</th>
                                    <th>{{ multi_lang('name') }}</th>
                                    <th>{{ multi_lang('email') }}</th>
                                    <th>{{ multi_lang('role') }}</th>
                                    <th>{{ multi_lang('last_login') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- Modal Form Add / Edit -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title_form"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="box_msg_user"></div>
				<form id="form_user" autocomplete="nope">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">{{ multi_lang('close') }}</button>
				<button type="button" class="btn btn-primary" id="btnSave" onclick="save()">{{ multi_lang('save') }}</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title_detail"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="body_detail">
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title_delete"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="body_delete">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="btnHapus">{{ multi_lang('delete') }}</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ multi_lang('close') }}</button>
			</div>
		</div>
	</div>
</div>

<style>
table.dataTable td, table.dataTable th {
    padding: 5px;
}
</style>

<!-- Page level custom scripts -->
<script>
    var table;
    var save_method;

    $(document).ready(function () {
        // $('#dataTable').DataTable(); // ID From dataTable with Hover
        table = $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ url('/user/data') }}",
                "data": {
                    "_token": "{{ csrf_token() }}"
                },
                "type": "POST",
                "dataType": "json",
            },
            "order": [[ 2, 'asc' ]], //Initial no order.

            "columnDefs": [
                {
                    "targets": [ 0,1 ], //last column
                    "orderable": false, //set not orderable
                },
                { "targets": 1, "width": '120px' },
                {"targets": 0, "width": '20px'}
            ],
        });
    });

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax
    }

    function add()
    {
        save_method = 'add';
        $('#form_user').html(""); // reset form on modals
        $('#body_detail').html("");
        $("#box_msg_user").html('').hide();
        $('#btnSave').text("{{ multi_lang('save') }}");
        $('#btnSave').attr('disabled',false);
        $('#title_form').text("{{ multi_lang('add') }} {{ $title }}"); // Set Title to Bootstrap modal title

        $.ajax({
            url : "{{ url('/user/add-view') }}",
            type: "GET",
            dataType: "JSON",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    await $('#form_user').html(data.html);
                }else{
                    toastr.error(xhr.statusText);
                }
                $('#modal_form').modal('show'); // show bootstrap modal
            }
        });
    }

    function edit(id)
    {
        save_method = 'edit';
        $('#form_user').html(""); // reset form on modals
        $('#body_detail').html("");
        $("#box_msg_user").html('').hide();
        $('#btnSave').text("{{ multi_lang('save') }}");
        $('#btnSave').attr('disabled',false);
        $('#title_form').text("{{ multi_lang('edit') }} {{ $title }}"); // Set Title to Bootstrap modal title

        $.ajax({
            url : "{{ url('/user/edit-view') }}/" + id,
            type: "GET",
            dataType: "JSON",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    await $('#form_user').html(data.html);
                }else{
                    toastr.error(xhr.statusText);
                }

                $('#modal_form').modal('show'); // show bootstrap modal

            }
        });
    }

    function save()
    {
        $('#btnSave').text("{{ multi_lang('process') }}..."); //change button text
        $('#btnSave').attr('disabled',true); //set button disable
        var url;

        if(save_method == 'add') {
            url = "{{ url('/user/add') }}";
        } else {
            url = "{{ url('/user/edit') }}";
        }

        var data_form = $('#form_user').serialize()+ "&" + $.param({_token:"{{ csrf_token() }}"});
        $.ajax({
            url : url,
            type: "POST",
            data: data_form,
            dataType: "json",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    if(data.status)
                    {
                        $('#modal_form').modal('toggle');
                        $("#box_msg_user").html('').hide();
                        await reload_table();
                        await toastr.success(data.message);
                    }
                    else
                    {
                        await $('#box_msg_user').html(data.message).fadeOut().fadeIn();
                        $('#modal_form').animate({ scrollTop: 0 }, 'slow');
                    }
                }else{
                    $('#modal_form').modal('toggle');
                    toastr.error(xhr.statusText);
                }

                $('#btnSave').text("{{ multi_lang('save') }}");
                $('#btnSave').attr('disabled',false);

            }
        });
    }

    function detail(id)
    {
        $('#title_detail').text("{{ multi_lang('detail') }} {{ $title }}"); // Set Title to Bootstrap modal title
        $('#form_user').html("");

        $.ajax({
            url : "{{ url('/user/detail') }}/" + id,
            type: "GET",
            dataType: "JSON",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    $('#body_detail').html(data.html);
                }else{
                    toastr.error(xhr.statusText);
                }

                $('#modal_detail').modal('show'); // show bootstrap modal

            }
        });
    }

    function deletes(id,name)
    {
        $('#modal_delete').modal('show'); // show bootstrap modal when complete loaded
        $('#title_delete').text("{{ multi_lang('delete') }} {{ $title }}"); // Set title to Bootstrap modal title
        $("#body_delete").html("{{ multi_lang('delete') }} {{ $title }} <b>"+name+"</b> ?");
        $('#btnHapus').attr("onclick", "process_delete('"+id+"')");
    }

    function process_delete(id)
    {
        $('#btnHapus').text("{{ multi_lang('process') }}..."); //change button text
        $('#btnHapus').attr('disabled',true); //set button disable

        $.ajax({
            url : "{{ url('/user/delete') }}/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    toastr.success(data.message);
                    reload_table();
                }else{
                    toastr.error(xhr.statusText);
                }

                $('#btnHapus').text('Delete');
                $('#btnHapus').attr('disabled',false);
                $('#modal_delete').modal('toggle');

            }
        });
    }
</script>
@endsection
