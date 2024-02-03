@extends('layout')

@section('content')
<header id="header">
    <h1>
    <a href="{{ route('index') }}">pastebin</a> /
    @if (empty($paste->title))
        <span class="empty">untitled</span>
    @else
        {{ $paste->title }}
    @endif
    </h1>
    <aside>
        <span>{{ $language }}</span> pasted on <span>{{ $paste->created_at->format('d.m.Y') }}</span>
        by <span>{{ $paste->author ?? 'anonymous' }}</span>
        <span class="mobile-break separator">/</span>
        <a href="{{ route('show', $paste->slug) }}">go back</a>

        @if ($paste->description)
            <p>{{ $paste->description }}</p>
        @endif
    </aside>
</header>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form action="{{ route('remove', $paste->slug) }}" method="post" id="paste-form">
            <p>To remove given paste please provide the key chosen during its creation.</p>

            <p>Paste will be removed <strong>permanently</strong> with no way to restore it.</p>

            <label for="key" class="required">Key</label>
            <input type="password" name="key" id="key">

            <button type="submit" class="button">Remove paste</button>
        </form>
    </div>
</div>
@endsection
