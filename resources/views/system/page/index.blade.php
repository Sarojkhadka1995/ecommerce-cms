@extends('system.layouts.listing')
@section('header')
<x-system.search-form :action="$indexUrl">
    <x-slot name="inputs">
        <x-system.form.form-inline-group :input="['name' => 'keyword', 'placeholder' => 'Keyword', 'default' => Request::get('keyword')]"></x-system.form.form-inline-group>
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
        <a href="{{ route('changePageStatus', ['id' => $item->id]) }}">
            @if ($item->status)
                <span class="badge badge-success">
                    {{ translate('Active') }}
                </span>
            @else
                <span class="badge badge-danger">
                    {{ translate('Not Active') }}
                </span>
            @endif
        </a>
    </td>
    <td>
        @include('system.partials.editButton')
        @include('system.partials.deleteButton')
    </td>
</tr>
@endforeach
@endsection
