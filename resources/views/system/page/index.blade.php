@extends('system.layouts.listing')
@section('header')
    <x-system.search-form :action="$indexUrl">
        <x-slot name="inputs">
            <x-system.form.form-inline-group
                :input="['name' => 'keyword', 'placeholder' => 'Keyword', 'default' => Request::get('keyword')]"></x-system.form.form-inline-group>
        </x-slot>
    </x-system.search-form>
@endsection

@section('table-heading')
    <tr>
        <th scope="col">{{translate('S.N')}}</th>
        <th scope="col">{{translate('Name')}}</th>
        <th scope="col">{{translate('Slug')}}</th>
        <th scope="col">{{translate('Status')}}</th>
        <th scope="col">{{translate('Action')}}</th>
    </tr>
@endsection

@section('table-data')
    @php $pageIndex = pageIndex($items); @endphp
    @foreach($items as $key=>$item)
        <tr>
            <td>{{SN($pageIndex, $key)}}</td>
            <td>{{ $item->title }}</td>
            <td>
                {{ $item->slug }}
            </td>
            <td>
                <button class="update-button btn {{!empty($item->status) ? 'btn-primary' : 'btn-danger'}}"
                        data-update-url="{{route('changeStatus', ['id' => $item->id])}}">
                    <em class="fa fa-refresh"></em>
                    @if ($item->status)
                        {{ translate('Active') }}
                    @else
                        {{ translate('In-Active') }}
                    @endif
                </button>
            </td>
            <td>
                @include('system.partials.editButton')
                @include('system.partials.deleteButton')
            </td>
        </tr>
    @endforeach

    <div class="modal fade" id="statusChangeModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="statusForm" method="GET" action="">
                <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusChangeModal">{{ translate('Confirm Update') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body message-body">
                        <strong>{{ translate('Are you sure you want to update status?') }}</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                            <em class="glyph-icon icon-close"></em>
                            {{ translate('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary" id="confirmUpdate">
                            <em class="glyph-icon icon-trash"></em>
                            {{ translate('Update') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openStatusModal(url) {
            $('#statusForm').attr('action', url);
            $('#statusChangeModal').modal('show');
        }

        // Attach a click event to your delete buttons
        $('.update-button').on('click', function () {
            const statusUpdateUrl = $(this).data('update-url');
            openStatusModal(statusUpdateUrl);
        });

    </script>
@endsection
