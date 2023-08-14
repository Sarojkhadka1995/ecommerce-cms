@props(['action', 'method'])
<form method="{{ $method ?? 'get' }}" action="{{$action}}" class="mb-2">
    <div class="row g-3">
        {{$inputs}}
        <div class="col-md-2">
            <button class="btn btn-primary mb-2" type="submit"><em class="fa fa-search"></em> {{translate('Search')}}</button>
            <a href="{{ $action }}" class="btn btn-danger mb-2 ml-2"><em class="fa fa-refresh"></em> {{translate('Reset')}}</a>
        </div>
    </div>
</form>
