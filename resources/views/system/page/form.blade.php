@extends('system.layouts.form')
@section('inputs')
    <x-system.form.form-group :input="[
        'name' => 'title',
        'required' => 'true',
        'id' => 'title',
        'label' => 'Page Title',
        'default' => $item->title ?? old('title'),
        'error' => $errors->first('title'),
    ]" />
    <x-system.form.form-group :input="[
        'name' => 'slug',
        'required' => 'true',
        'id' => 'slug',
        'label' => 'Page Slug',
        'default' => $item->slug ?? old('slug'),
        'error' => $errors->first('slug'),
    ]" />
    <x-system.form.form-group :input="['name' => 'image', 'label' => 'Image']">
        <x-slot name="inputs">

            <x-system.form.input-file :input="[
                'name' => 'image',
                'label' => 'Image',
                'accept' => 'image/*',
                'default' => $item->image ?? '',
                'type' => 'file',
                'id' => 'img-icon',
                'error' => $errors->first('image'),
                'onchange' => 'loadIcon(event)',
            ]" />

            <div>
                @if (isset($item) && $item->image != null && Storage::disk('public')->exists('/uploads/page/' . $item->image))
                    <div class="page_image">
                        <img src="{{ Storage::disk('public')->url('uploads/page/' . $item->image) }}" alt="Profile Pic"
                            class="img-fluid" style="max-height:150px;">
                    </div>
                @endif
            </div>
        </x-slot>

    </x-system.form.form-group>
    <x-system.form.form-group :input="['label' => 'Page Description']">
        <x-slot name="inputs">
            <x-system.form.text-area :input="[
                'name' => 'description',
                'required' => 'true',
                'label' => 'Description',
                'editor' => true,
                'default' => $item->description ?? '',
                'error' => $errors->first('description'),

            ]" />
        </x-slot>
    </x-system.form.form-group>
    <x-system.form.form-group :input="[
        'name' => 'meta_title',
        'required' => 'true',
        'id' => 'seoTitle',
        'label' => 'Meta Title',
        'default' => $item->meta_title ?? old('meta_title'),
        'error' => $errors->first('meta_title'),


    ]" />
    <x-system.form.form-group :input="['label' => 'Meta Description']">
        <x-slot name="inputs">
            <x-system.form.text-area :input="[
                'name' => 'meta_description',
                'label' => 'Meta Description',
                'default' => $item->meta_description ?? '',
                'error' => $errors->first('meta_description'),
            ]" />
        </x-slot>
    </x-system.form.form-group>

    <div class="theme-form g-3 row mb-3" id="">
        <label class="input-label col-sm-2 form-label">Tag</label>
        <div class="col-sm-6">
                <input type="text" class="form-control" placeholder="keywords" data-role="tagsinput" name="keywords" value="{{ isset($item)? $item->keywords: ''}}">
        </div>
    </div>

    <x-system.form.form-group :input="['label' => 'Status']">
        <x-slot name="inputs">
            <x-system.form.input-radio :input="['name' => 'status', 'options' => $status, 'default' => $item->status ?? 1]" />
        </x-slot>
    </x-system.form.form-group>
@endsection


@section('scripts')
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"
  ></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
  <script>
    $(function () {
      $('input')
        .on('change', function (event) {
          var $element = $(event.target);
          var $container = $element.closest('.example');

          if (!$element.data('tagsinput')) return;

          var val = $element.val();
          if (val === null) val = 'null';
          var items = $element.tagsinput('items');

          $('code', $('pre.val', $container)).html(
            $.isArray(val)
              ? JSON.stringify(val)
              : '"' + val.replace('"', '\\"') + '"'
          );
          $('code', $('pre.items', $container)).html(
            JSON.stringify($element.tagsinput('items'))
          );
        })
        .trigger('change');
    });
  </script>
    <script>
        $(document).ready(function() {
            $('input[name="title"]').keyup(function(e) {
                var value = $(this).val();
                $('#slug').val(value);
            });
        });
    </script>
@endsection
