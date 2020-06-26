<div class="form-group row" id="{{ $input['groupId'] ?? '' }}">
    <label for="{{ $input['name'] }}" class="col-sm-2 col-form-label {{ isset($input['required']) ? 'require' : '' }}">
        {{ isset($input['label']) ? trans($input['label']) : '' }}
    </label>
    <div class="{{ isset($input['fullWidth']) ? 'col-sm-10' : 'col-sm-6' }}">
        @if(isset($inputs))
        {{$inputs}}
        @else
        <x-system.form.input-normal :input="array_merge($input, ['helpText' => false])"/>
        @endif
        @if(isset($input['helpText']))
        <small class="form-text text-muted">{{ trans($input['helpText']) ?? '' }}</small>
        @endif
        @if(isset($input['error']))<div class="invalid-feedback">{{ trans($input['error']) }}</div>@endif
    </div>
</div>