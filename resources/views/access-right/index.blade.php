@extends('layouts.main')
@section('container')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		<div class="col-sm-12">
			<ol class="breadcrumb float-sm-left">
                {!! breadcrumb() !!}
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
                <div class="card mb-4 p-3">
                    <div>
                        <button class="btn btn-success mb-3" onclick="add()"><i class="fas fa-plus mr-2"></i> Add</button>
                    </div>
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="dataTable" aria-describedby="data-user">
                        <thead>
                            <tr>
                                <th>{{ multi_lang('number') }}</th>
                                <th>{{ multi_lang('action') }}</th>
                                <th>{{ multi_lang('name') }}</th>
                                <th>{{ multi_lang('home_menu') }}</th>
                            </tr>
                        </thead>
                    </table>
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
				<b class="modal-title" id="title_form"></b>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_data" autocomplete="nope">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ multi_lang('close') }}</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ multi_lang('close') }}</button>
			</div>
		</div>
	</div>
</div>

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
                "url": "{{ url('/access-right/data') }}",
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
                {
                    "targets": 0,
                    "width": '5%'
                },
                {
                    "targets": 1,
                    "width": '10%'
                },
            ],
            "language": {
                "decimal":        "",
                "emptyTable":     "{{ multi_lang('dt_empty_table') }}",
                "info":           "{{ multi_lang('dt_info') }}",
                "infoEmpty":      "{{ multi_lang('dt_info_empty') }}",
                "infoFiltered":   "{{ multi_lang('dt_info_filtered') }}",
                "infoPostFix":    "",
                "thousands":      "{{ multi_lang('dt_thousands') }}",
                "lengthMenu":     "{{ multi_lang('dt_length_menu') }}",
                "loadingRecords": "{{ multi_lang('dt_loading_records') }}",
                "processing":     "",
                "search":         "{{ multi_lang('dt_search') }}",
                "zeroRecords":    "{{ multi_lang('dt_zero_ecords') }}",
                "paginate": {
                    "first":      "{{ multi_lang('dt_paginate_first') }}",
                    "last":       "{{ multi_lang('dt_paginate_last') }}",
                    "next":       "{{ multi_lang('dt_paginate_next') }}",
                    "previous":   "{{ multi_lang('dt_paginate_previous') }}"
                },
                "aria": {
                    "sortAscending":  "{{ multi_lang('dt_aria_sort_ascending') }}",
                    "sortDescending": "{{ multi_lang('dt_aria_sort_descending') }}"
                }
            }
        });
    });

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax
    }

    function add()
    {
        save_method = 'add';
        $('#form_data').html("");

        @if(!empty($language_master_json))
        var menu_master_json = {!! $language_master_json !!}
        menu_master_json.forEach(function(item, index){
            $("#inputName_"+item.id).removeClass('is-invalid');
            $("#msg_name_"+item.id).removeClass('d-block');
        });
        @endif

        $("#inputHomeMenu").removeClass('is-invalid');
        $("#msg_home_menu").removeClass('d-block');

        $("#inputMenuAccess").removeClass('is-invalid').addClass('border');
        $("#msg_menu_access").removeClass('d-block');

        $('#btnSave').text("{{ multi_lang('save') }}");
        $('#btnSave').attr('disabled',false);

        $('#title_form').text("{{ multi_lang('add') }} {{ $title }}");

        $.ajax({
            url : "{{ url('/access-right/add') }}",
            type: "GET",
            dataType: "html",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    await $('#form_data').html(data);
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
        $('#form_data').html("");

        @if(!empty($language_master_json))
        var menu_master_json = {!! $language_master_json !!}
        menu_master_json.forEach(function(item, index){
            $("#inputName_"+item.id).removeClass('is-invalid');
            $("#msg_name_"+item.id).removeClass('d-block');
        });
        @endif

        $("#inputHomeMenu").removeClass('is-invalid');
        $("#msg_home_menu").removeClass('d-block');

        $("#inputMenuAccess").removeClass('is-invalid').addClass('border');
        $("#msg_menu_access").removeClass('d-block');

        $('#btnSave').text("{{ multi_lang('save') }}");
        $('#btnSave').attr('disabled',false);

        $('#title_form').text("{{ multi_lang('edit') }} {{ $title }}");

        $.ajax({
            url : "{{ url('/access-right/edit') }}/" + id,
            type: "GET",
            dataType: "html",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    await $('#form_data').html(data);

                    var menu_data = $("#menugroup_menu_id").val();
                    if(menu_data !== undefined && menu_data != ""){
                        var menu_arr = menu_data.split(",");

                        menu_arr.forEach(function(item, index){
                            $('#menu_lable_'+item).html("{{ multi_lang('yes') }}");
                            $('#menu_name_'+item).css({opacity: 1});
                            $('#menu_'+item).prop('checked', true);
                        });
                    }
                }else{
                    toastr.error(xhr.statusText);
                }
                $('#modal_form').modal('show'); // show bootstrap modal

            }
        });
    }

    function save()
    {
        @if(!empty($language_master_json))
        var menu_master_json = {!! $language_master_json !!}
        menu_master_json.forEach(function(item, index){
            $("#inputName_"+item.id).removeClass('is-invalid');
            $("#msg_name_"+item.id).removeClass('d-block').hide();
        });
        @endif

        $("#inputHomeMenu").removeClass('is-invalid');
        $("#msg_home_menu").removeClass('d-block');

        $("#inputMenuAccess").removeClass('is-invalid').addClass('border');
        $("#msg_menu_access").removeClass('d-block');

        $('#btnSave').text("{{ multi_lang('process') }}..."); //change button text
        $('#btnSave').attr('disabled',true); //set button disable
        var url;

        if(save_method == 'add') {
            url = "{{ url('/access-right/add') }}";
        } else {
            url = "{{ url('/access-right/edit') }}";
        }

        var data_form = $('#form_data').serialize()+ "&" + $.param({_token:"{{ csrf_token() }}"});
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
                        await reload_table();
                        await toastr.success(data.message);
                    }
                    else
                    {
                        @if(!empty($language_master_json))
                        var menu_master_json = {!! $language_master_json !!}
                        menu_master_json.forEach(function(item, index){
                            if(data.message_item.name !== undefined && data.message_item.name.length > 0 && data.message_item.name[index] != ""){
                                $("#inputName_"+item.id).addClass('is-invalid');
                                $("#msg_name_"+item.id).html(data.message_item.name[index]).addClass('d-block');
                            }
                        });
                        @endif

                        if(data.message_item.home_menu !== undefined && data.message_item.home_menu != ""){
                            $("#inputHomeMenu").addClass('is-invalid');
                            $("#msg_home_menu").html(data.message_item.home_menu).addClass('d-block');
                        }

                        if(data.message_item.menu_access !== undefined && data.message_item.menu_access != ""){
                            $("#inputMenuAccess").addClass('is-invalid').css({border: "1px #DC3544 solid"}).removeClass('border');
                            $("#msg_menu_access").html(data.message_item.menu_access).addClass('d-block');
                        }

                        $('#modal_form').animate({ scrollTop: $('.is-invalid:first').offset().top }, 'slow');
                    }
                }else{
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
        $('#form_data').html("");

        $.ajax({
            url : "{{ url('/access-right/detail') }}/" + id,
            type: "GET",
            dataType: "html",
            success: async function(data, textStatus, xhr)
            {
                if(xhr.status == '200'){
                    $('#body_detail').html(data);

                    var menu_data = $("#menugroup_menu_id").val();
                    if(menu_data !== undefined && menu_data != ""){
                        var menu_arr = menu_data.split(",");

                        menu_arr.forEach(function(item, index){
                            $('#menu_lable_'+item).html("{{ multi_lang('yes') }}");
                            $('#menu_name_'+item).css({opacity: 1});
                            $('#menu_'+item).prop('checked', true);
                        });
                    }
                    $('.menu_cb').attr("disabled", true);
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
            url : "{{ url('/access-right/delete') }}/" + id,
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

                $('#btnHapus').text("{{ multi_lang('delete') }}");
                $('#btnHapus').attr('disabled',false);
                $('#modal_delete').modal('toggle');

            }
        });
    }

    function switch_menu_access(menu_id, parent, child){
        var parent_arr = parent.split(",");
        var child_arr = child.split(",");

        if($('#menu_'+menu_id).is(':checked')){
            $('#menu_lable_'+menu_id).html("{{ multi_lang('yes') }}");
            $('#menu_name_'+menu_id).css({opacity: 1});

            parent_arr.forEach(function(item, index){
                $('#menu_lable_'+item).html("{{ multi_lang('yes') }}");
                $('#menu_name_'+item).css({opacity: 1});
                $('#menu_'+item).prop('checked', true);
            });

            child_arr.forEach(function(item, index){
                $('#menu_lable_'+item).html("{{ multi_lang('yes') }}");
                $('#menu_name_'+item).css({opacity: 1});
                $('#menu_'+item).prop('checked', true);
            });
        }else{
            $('#menu_lable_'+menu_id).html("{{ multi_lang('no') }}");
            $('#menu_name_'+menu_id).css({opacity: 0.5});

            child_arr.forEach(function(item, index){
                $('#menu_lable_'+item).html("{{ multi_lang('no') }}");
                $('#menu_name_'+item).css({opacity: 0.5});
                $('#menu_'+item).prop('checked', false);
            });
        }
    }
</script>
@endsection
