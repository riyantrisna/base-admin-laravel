<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('name') }}</div>
    <div class="col-sm-7">
        {{ $detail->name }}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('email') }}</div>
    <div class="col-sm-7">
        {{ $detail->email }}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('role') }}</div>
    <div class="col-sm-7">
        {{ $detail->role_name }}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('language') }}</div>
    <div class="col-sm-7">
        {{ $detail->lang_code_name }}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('status') }}</div>
    <div class="col-sm-7">
        {{ $detail->status }}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('created') }}</div>
    <div class="col-sm-7">
        {{ $detail->created }}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3 text-bold">{{ multi_lang('updated') }}</div>
    <div class="col-sm-7">
        {{ $detail->updated }}
    </div>
</div>
