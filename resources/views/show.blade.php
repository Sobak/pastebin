@extends('layout')

@section('content')
<header id="header">
    @if (empty($paste->title))
        <h1 class="empty">untitled</h1>
    @else
        <h1>{{ $paste->title }}</h1>
    @endif
    <aside>
        <span>{{ $paste->language or 'text' }}</span> pasted on <span>{{ $paste->created_at->format('d.m.Y') }}</span>
        by <span>{{ $paste->author or 'anonymous' }}</span>
        <span class="mobile-break separator">/</span>
        <a href="{{ route('edit', $paste->slug) }}">edit</a>
        <span class="separator">/</span>
        <a href="{{ route('raw', $paste->slug) }}">raw</a>
        <span class="separator">/</span>
        <a href="{{ route('download', $paste->slug) }}">download</a>

        @if ($paste->description)
            <p>{{ $paste->description }}</p>
        @endif
    </aside>
</header>

<pre class="keylighter">{!! $content !!}</pre>
@endsection
