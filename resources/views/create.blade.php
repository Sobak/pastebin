@extends('layout')

@section('content')
<form action="{{ route('index') }}" method="post" id="paste-form">
    <label for="title">Title</label>
    <input name="title" id="title" value="{{ old('title') }}" autofocus>

    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-12">
                    <label for="language">Language <span class="label-help">(<a href="#" data-modal="#modal_help_language">help</a>)</span></label>
                    <input name="language" id="language" maxlength="60" value="{{ old('language') }}">
                </div>
                <div class="col-md-6">
                    <label for="author">Author</label>
                    <input name="author" id="author" value="{{ old('author') }}">
                </div>
                <div class="col-md-6">
                    <label for="key">Key <span class="label-help">(<a href="#" data-modal="#modal_help_key">help</a>)</span></label>
                    <div id="key-input">
                        <input type="password" name="key" id="key">
                        <button id="key-save" type="button" tabindex="-1" title="Remember the key">Set</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="6">{{ old('description') }}</textarea>
        </div>
    </div>
    <label for="content" class="required">Code</label>
    <textarea name="content" id="content" class="code" cols="30" rows="20">{{ old('content') }}</textarea>

    <button type="submit" class="button">paste</button>
</form>

@include('partials.form-help-modals')
@endsection
