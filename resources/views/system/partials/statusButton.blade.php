@if(hasPermission($indexUrl.'/'.$item->id, 'get'))
    <button type="button" class="btn btn-danger btn-sm btn-primary" data-toggle="modal" data-target="#confirmStatusModal"
            data-href="{{url($indexUrl.'/'.$item->id)}}">
        <em class="fa fa-trash"></em> {{ translate('Change') }}
    </button>
@endif
