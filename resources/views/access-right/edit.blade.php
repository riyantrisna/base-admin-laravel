<div class="form-group row">
    <label for="inputName" class="col-sm-3 col-form-label font-weight-normal">{{ multi_lang('name') }} <span class="text-red">*</span></label>
    <div class="col-sm-9">
        <input type="hidden" name="menugroup_id" value="{{ $detail->menugroup_id }}"/>
        <input type="hidden" id="menugroup_menu_id" value="{{ $detail->menugroup_menu_id }}"/>
    @foreach ($language_master as $language)
        @foreach ($detail_name as $name)
            @if($name->menugroupname_lang_code == $language->id)
            <div class="text-bold">{{ $language->name }}</div>
            <input type="text" class="form-control" id="inputName_{{ $language->id }}" name="name[{{ $language->id }}]" placeholder="{{ multi_lang('ex_cashier', $language->id) }}" value="{{ $name->menugroupname_name }}">
            <input type="hidden" name="name_name[{{ $language->id }}]" value="{{ $language->name }}"/>
            <div class="invalid-feedback" id="msg_name_{{ $language->id }}"></div>
            <div class="mb-2"></div>
            @endif
        @endforeach
    @endforeach
    </div>
</div>
<div class="form-group row">
    <label for="inputHomeMenu" class="col-sm-3 col-form-label font-weight-normal">{{ multi_lang('home_menu') }} <span class="text-red">*</span></label>
    <div class="col-sm-9">
        <select class="form-control" id="inputHomeMenu" name="home_menu">
            <option value="">-- {{ multi_lang('select') }} --</option>
            @foreach ($home_menu_master as $menu)
                <option value="{{ $menu->id }}" @if($detail->menugroup_home_menu_id == $menu->id) selected @endif>{{ $menu->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="msg_home_menu"></div>
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
