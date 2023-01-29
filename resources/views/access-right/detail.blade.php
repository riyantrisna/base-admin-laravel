<div class="form-group row">
    <label for="inputName" class="col-sm-3 col-form-label font-weight-normal">{{ multi_lang('name') }} <span class="text-red">*</span></label>
    <div class="col-sm-9">
        <input type="hidden" id="menugroup_menu_id" value="{{ $detail->menugroup_menu_id }}"/>
    @foreach ($language_master as $language)
        @foreach ($detail_name as $name)
            @if($name->menugroupname_lang_code == $language->id)
                <div class="text-bold">{{ $language->name }}</div>
                {{ $name->menugroupname_name }}
            @endif
        @endforeach
    @endforeach
    </div>
</div>
<div class="form-group row">
    <label for="inputHomeMenu" class="col-sm-3 col-form-label font-weight-normal">{{ multi_lang('home_menu') }} <span class="text-red">*</span></label>
    <div class="col-sm-9">
        {{ $detail->menuname_name }}
    </div>
</div>
<div class="form-group row">
    <label for="inputMenuAccess" class="col-sm-3 col-form-label font-weight-normal">{{ multi_lang('menu_access') }} <span class="text-red">*</span></label>
    <div class="col-sm-9">
        <div id="inputMenuAccess" class="row p-1 border" style="margin: 1px;">
            {{ echo_menu_access() }}
            <div class="p-1"></div>
        </div>
        <div class="invalid-feedback" id="msg_menu_access"></div>
    </div>
</div>
