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
        <span>{{ $paste->language ?? 'text' }}</span> pasted on <span>{{ $paste->created_at->format('d.m.Y') }}</span>
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

            <label for="key">Key</label>
            <input name="key" id="key">

            <button type="submit" class="button">Remove paste</button>
        </form>
    </div>
</div>
@endsection
