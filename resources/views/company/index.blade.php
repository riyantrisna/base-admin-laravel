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
            <div class="col-lg-12">
                <div class="card mb-4 p-3">
                    <form id="form_data" autocomplete="nope">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('logo') }}</label>
                            <div class="col-sm-8">
                                <label id="label_images" for="images" style="cursor: pointer; @if(!empty($detail->company_logo)) display:none; @endif">
                                    <img class="image_tag" style="width:150px; height:150px; border:1px dashed #C3C3C3;" src="{{ asset('assets/upload/company/upload-image.png')}}" />
                                </label>

                                <input type="file" name="images" id="images" style="display:none;" onchange="readURL(this)" accept="image/*"/>

                                <img class="image_tag" style="width:150px; height:150px; border:1px dashed #C3C3C3; margin-bottom: 5px; @if(empty($detail->company_logo)) display:none; @endif" id="show_images" @if(!empty($detail->company_logo)) src="{{ $path_company_logo.$detail->company_logo }}" @endif/>
                                <br>
                                <div style="padding-left: 2.5em!important;">
                                    <span id="remove" class="btn btn-warning" onclick="removeImage()" style="cursor: pointer; margin-bottom: 5px; @if(empty($detail->company_logo)) display:none; @endif">
                                        {{ multi_lang('delete') }}
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="msg_logo"></div>
                                <input type="hidden" id="file_image_value" name="file_image_value" value="{{ $detail->base_64_images }}"/>
                                <input type="hidden" id="file_image_value_old" name="file_image_value_old" value="{{ $detail->company_logo }}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('name') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputName" name="name" value="{{ $detail->company_name }}">
                                <div class="invalid-feedback" id="msg_name"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPhone" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('phone') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputPhone" name="phone" value="{{ $detail->company_phone }}">
                                <div class="invalid-feedback" id="msg_phone"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputAddress" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('address') }}</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="inputAddress" name="address">{{ $detail->company_address }}</textarea>
                                <div class="invalid-feedback" id="msg_address"></div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer pb-0">
                        <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">{{ multi_lang('save') }}</button>
                    </div>
                </div>
            </div>
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- Page level custom scripts -->
<script>

    $(document).ready(function () {

    });

    function save()
    {

        $("#image_tag").removeClass('is-invalid');
        $("#msg_logo").removeClass('d-block');

        $('#btnSave').text("{{ multi_lang('process') }}..."); //change button text
        $('#btnSave').attr('disabled',true); //set button disable

        url = "{{ url('/company') }}";

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
                        $("#file_image_value_old").val(data.new_file);
                        await toastr.success(data.message);

                        $('html').animate({ scrollTop: 0}, 'slow');
                    }
                    else
                    {
                        if(data.message_item.logo !== undefined && data.message_item.logo != ""){
                            $("#image_tag").addClass('is-invalid');
                            $("#msg_logo").html(data.message_item.logo).addClass('d-block');
                        }

                        $('#btnSave').text("{{ multi_lang('save') }}");
                        $('#btnSave').attr('disabled',false);

                        $('html').animate({ scrollTop: 0}, 'slow');
                    }
                }else{
                    toastr.error(xhr.statusText);
                }

                $('#btnSave').text("{{ multi_lang('save') }}");
                $('#btnSave').attr('disabled',false);

            }
        });
    }

    function readURL(input) {

        var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF'];

        $('.msg_images').html('');
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            if(input.files[0].size <= 1024000){

                var extension = input.files[0].name.split('.').pop().toLowerCase(),
                isSuccess = fileTypes.indexOf(extension) > -1;

                if(isSuccess){
                    reader.onload = function (e) {
                        $('#label_images').hide();
                        $('#show_images').attr('src', e.target.result).fadeOut().fadeIn();
                        $('#file_image_value').val(e.target.result);
                        $('#remove').show();
                    };
                    reader.readAsDataURL(input.files[0]);
                }else{
                    $('#msg_images').html("{{ multi_lang('allowed_file_is') }} jpg, JPG, jpeg, JPEG, png, PNG, gif, GIF");
                }
            }else{
                $('#msg_images').html('{{ multi_lang('max_file_is') }} 1024KB');
            }
        }
    }

    function removeImage()
    {
        $('#label_images').show();
        $('#show_images').removeAttr('src').hide();
        $('#images').val('');
        $('#file_image_value').val('');
        $('#remove').hide();
        $('.msg_images').html('');
    }
</script>
@endsection
