@extends('system.layouts.form')
@section('inputs')
    <x-system.form.form-group
        :input="[ 'name' => 'name', 'label'=> 'Name', 'required' => true, 'default' => old('name') ?? $item->name ?? '', 'error' => $errors->first('name')]"/>
    <x-system.form.form-group
        :input="[ 'name' => 'username', 'label'=> 'Username', 'required' => true, 'default' => old('username') ?? $item->username ?? '', 'error' => $errors->first('username')]"/>
    <x-system.form.form-group
        :input="[ 'name' => 'email', 'label'=> 'Email', 'required' => true, 'default' => old('email') ?? $item->email ?? '', 'error' => $errors->first('email')]"/>
    <x-system.form.form-group
        :input="[ 'name' => 'role_id', 'label'=> 'Role', 'required'=>'true']">
        <x-slot name="inputs">
            <select class="form-control select2" id="roleId" name="role_id[]" multiple="multiple"
                    data-placeholder="{{ translate('Select Roles') }}">
                @foreach($roles as $key => $role)
                    <option value="{{ $key }}"
                            @if(is_array(old('role_id')) && in_array($key, old('role_id')))
                                selected
                            @elseif(isset($roleUsers) && in_array($key, $roleUsers))
                                selected
                        @endif
                    >{{ $role }}</option>
                @endforeach
            </select>

            @error('role_id')
            <div class="invalid-feedback">{{ translate($message) }}</div>
            @enderror
        </x-slot>
    </x-system.form.form-group>

    <x-system.form.form-group
        :input="[ 'name' => 'is_2fa_enabled', 'label'=> 'Two Factor Authentication', 'required' => true]">
        <x-slot name="inputs">
            <x-system.form.input-radio :input="[ 'name' => 'is_2fa_enabled', 'label'=> 'Two Factor Authentication', 'required' => true,
    'default' => old('is_2fa_enabled') ?? $item->is_2fa_enabled ?? 0, 'options' => [[ 'value' => '1', 'label' => 'Active'], ['value' => '0', 'label'=>'In-Active']]]"/>
        </x-slot>
    </x-system.form.form-group>

    @if(!isset($item))
        <x-system.form.form-group
            :input="[ 'name' => 'set_password_status', 'label'=> 'Set Password ?', 'required' => true]">
            <x-slot name="inputs">
                <x-system.form.input-radio :input="[ 'name' => 'set_password_status', 'label'=> 'Set Password', 'required' => true,
    'default' => old('set_password_status') ?? 0, 'options' => [[ 'value' => '0', 'label' => 'Send Activation Link'], ['value' => '1', 'label'=>'Set Password']]]"/>
            </x-slot>
        </x-system.form.form-group>

        <div class="d-none" id="password-inputs">
            <x-system.form.form-group
                :input="[ 'name' => 'password', 'label'=> 'Password','label-required'=>true, 'type' => 'password', 'default' => old('password'), 'error' => $errors->first('password')]"/>
            <x-system.form.form-group
                :input="[ 'name' => 'password_confirmation', 'label'=> 'Confirm Password','label-required'=>true, 'type' => 'password', 'default' => old('password_confirmation'), 'error' => $errors->first('password_confirmation')]"/>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Target the select element by its ID and initialize Select2
            $('#roleId').select2({
                placeholder: 'Select Roles',
                width: 'resolve',
            });
        });
    </script>
@endsection
