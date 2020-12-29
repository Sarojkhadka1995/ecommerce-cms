@extends('system.layouts.form')
@section('inputs')
<x-system.form.form-group :input="['name' => 'name','required'=>'true', 'label' => 'Category Name','default'=> $item->name ?? old('name'), 'error' => $errors->first('name')]" />
<x-system.form.form-group :input="['name' => 'attributes', 'required'=>'true', 'label' => 'Category Attribute','default'=> $item->attributes ?? old('attributes'), 'error' => $errors->first('attributes')]" />
<x-system.form.form-group :input="['label' => 'Category Description']">
    <x-slot name="inputs">
        <x-system.form.text-area :input="['name' => 'description', 'default'=> $item->description ?? old('description'), 'placeholder' => 'Category Description']" />
    </x-slot>
</x-system.form.form-group>
@endsection