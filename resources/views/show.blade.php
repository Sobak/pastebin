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
        <a href="{{ route('edit', $paste->slug) }}">edit</a>
        <span class="separator">/</span>
        <a href="{{ route('raw', $paste->slug) }}">raw</a>
        <span class="separator">/</span>
        <a href="{{ route('download', $paste->slug) }}">download</a>
        <span class="separator">/</span>
        <a href="{{ route('remove', $paste->slug) }}">remove</a>

        @if ($paste->description)
            <p>{{ $paste->description }}</p>
        @endif
    </aside>
</header>

<pre class="keylighter">{!! $content !!}</pre>
@endsection
