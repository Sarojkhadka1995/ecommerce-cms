@extends('system.layouts.listing')
@section('header')
    <x-system.search-form :action="$indexUrl">
        <x-slot name="inputs">
            <x-system.form.form-inline-group
                :input="['name' => 'keyword', 'label' => 'Search keyword', 'default' => Request::get('keyword')]"/>
        </x-slot>
    </x-system.search-form>
@endsection

@section('table-heading')
    <tr>
        <th scope="col">{{translate('S.N')}}</th>
        <th scope="col">{{translate('Name')}}</th>
        <th scope="col">{{translate('Action')}}</th>
    </tr>
@endsection

@section('table-data')
    @php $pageIndex = pageIndex($items); @endphp
    @foreach($items as $key=>$item)
        <tr>
            <td>{{SN($pageIndex, $key)}}</td>
            <td>{{$item->name}}</td>
            <td>
                @if($item->isEditable($item->id))
                    @include('system.partials.editButton')
                @endif

                @if($item->isDeletable($item->id))
                    @if($item->users->count() > 0)
                        @if(hasPermission($indexUrl.'/'.$item->id, 'delete'))
                            <button class="delete-button btn btn-danger"
                                    data-delete-url="{{url($indexUrl.'/'.$item->id)}}">
                                <em class="fa fa-trash"></em> {{ translate('Delete') }}
                            </button>
                        @endif
                    @else
                        @include('system.partials.deleteButton')
                    @endif
                @endif

    @if(hasPermission('/users'))
    <a href="/{{getSystemPrefix()}}/users?role={{$item->id}}" class="nonelink"><span class="span-icon">
        <i class="fa fa-users" aria-hidden="true"></i>{{ $item->users->count()}}</span>
                    </a>
                @endif
            </td>
        </tr>
    @endforeach

    <div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="deleteRoleForm" method="POST" action="">
                @method('DELETE')
                <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">{{ translate('Confirm Delete') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body message-body">
                        <strong>{{ translate('Are you sure you want to delete?') }}</strong>
                        <p>{{ translate('If you are sure that you want to do it, please select another role type to assign all the users of this type to when this role type is deleted.') }}</p>
                        <x-system.form.input-select
                            :input="[ 'name' => 'role_id', 'label'=> 'Role', 'required' => true, 'default' => old('role_id') ?? '' , 'options' => $roles, 'placeholder' => 'Select role', 'error' => $errors->first('role_id')]"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                            <em class="glyph-icon icon-close"></em>
                            {{ translate('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger" id="confirmDelete">
                            <em class="glyph-icon icon-trash"></em>
                            {{ translate('Delete') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function openDeleteModal(url) {
            $('#deleteRoleForm').attr('action', url);
            $('#deleteRoleModal').modal('show');
        }

        // Attach a click event to your delete buttons
        $('.delete-button').on('click', function () {
            const deleteUrl = $(this).data('delete-url');
            openDeleteModal(deleteUrl);
        });

    </script>
@endsection
