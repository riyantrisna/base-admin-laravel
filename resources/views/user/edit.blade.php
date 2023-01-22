<div class="form-group row">
    <label for="inputName" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('name') }} <span class="text-red">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="inputName" name="name" value="{{ $detail->name }}">
        <input type="hidden" name="id" value="{{ $detail->id }}">
        <div class="invalid-feedback" id="msg_name"></div>
    </div>
</div>
<div class="form-group row">
    <label for="inputEmail" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('email') }} <span class="text-red">*</span></label>
    <div class="col-sm-8">
        <input type="email" class="form-control" id="inputEmail" name="email" value="{{ $detail->email }}">
        <div class="invalid-feedback" id="msg_email"></div>
    </div>
</div>
<div class="form-group row">
    <label for="inputRole" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('role') }} <span class="text-red">*</span></label>
    <div class="col-sm-8">
        <select class="form-control" id="inputRole" name="role">
            <option value="">-- {{ multi_lang('select') }} --</option>
            @foreach ($role_master as $role)
                <option value="{{ $role->id }}" @if($role->id == $detail->role) selected @endif>{{ $role->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="msg_role"></div>
    </div>
</div>
<div class="form-group row">
    <label for="inputLanguage" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('language') }} <span class="text-red">*</span></label>
    <div class="col-sm-8">
        <select class="form-control" id="inputLanguage" name="language">
            <option value="">-- {{ multi_lang('select') }} --</option>
            @foreach ($language_master as $language)
                <option value="{{ $language->id }}" @if($language->id == $detail->lang_code) selected @endif>{{ $language->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="msg_language"></div>
    </div>
</div>
<div class="form-group row">
    <label for="inputStatus" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('status') }} <span class="text-red">*</span></label>
    <div class="col-sm-8">
        <select class="form-control" id="inputStatus" name="status">
            <option value="">-- {{ multi_lang('select') }} --</option>
            @foreach ($status_master as $status)
                <option value="{{ $status->id }}" @if($status->id == $detail->status) selected @endif>{{ $status->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="msg_status"></div>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-12">
        <span class="font-italic" style="font-size: 14px;">{{ multi_lang('note_edit_passwoer_user') }}</span>
    </div>
</div>
<div class="form-group row">
    <label for="inputPassword" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('password') }}</label>
    <div class="col-sm-8">
        <input type="password" class="form-control" id="inputPassword" name="password">
        <div class="invalid-feedback" id="msg_password"></div>
    </div>
</div>
<div class="form-group row">
    <label for="inputRePassword" class="col-sm-4 col-form-label font-weight-normal">{{ multi_lang('re_password') }}</label>
    <div class="col-sm-8">
        <input type="password" class="form-control" id="inputRePassword" name="re_password">
        <div class="invalid-feedback" id="msg_re_password"></div>
    </div>
</div>
