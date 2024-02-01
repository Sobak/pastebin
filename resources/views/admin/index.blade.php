@extends('admin.layout')

@section('admin-title')
All pastes
@endsection

@section('admin-content')
<div class="datatable-container">
    <div class="header-tools">
        <div class="tools">
            <button id="bulk_remove_button" form="bulk_remove_form" disabled>remove</button>
            @if (request()->fullUrl() === route('admin.index'))
                <span class="button disabled">reset filters</span>
            @else
                <a class="button" href="{{ route('admin.index') }}">reset filters</a>
            @endif
        </div>

        <div class="search">
            <form method="get" action="">
                <label for="admin_search">Search</label>
                <input type="search" name="search" class="search-input" id="admin_search" value="{{ request()->query->get('search') }}" placeholder="Search…">
                @if (request()->query->has('sort'))
                <input type="hidden" name="sort" value="{{ request()->query('sort') }}">
                @endif
            </form>
        </div>
    </div>

    <form method="post" action="{{ route('admin.mass-remove') }}" id="bulk_remove_form">
    <table class="datatable">
        <thead>
        <tr>
            <th><input type="checkbox" id="select_all" /></th>
            <th>{{ render_sortable_table_header('ID', 'id') }}</th>
            <th>{{ render_sortable_table_header('Title', 'title') }}</th>
            <th>{{ render_sortable_table_header('Author', 'author') }}</th>
            <th>{{ render_sortable_table_header('Language', 'language') }}</th>
            <th>{{ render_sortable_table_header('Size', 'size') }}</th>
            <th>Has key?</th>
            <th>Actions</th>
            <th>{{ render_sortable_table_header('Created at', 'created_at') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($pastes as $paste)
            <tr>
                <td><input type="checkbox" name="pastes[]" value="{{ $paste->id }}" /></td>
                <td>{{ $paste->id }}</td>
                <td>
                    @if ($paste->title)
                        <a href="{{ route('show', $paste->slug) }}">{{ $paste->title }}</a>
                    @else
                        <a href="{{ route('show', $paste->slug) }}" class="cell-empty">unknown</a>
                    @endif
                </td>
                <td>
                    @if ($paste->author)
                        <span title="{{ $paste->author_ip }}">{{ $paste->author }}</span>
                    @else
                        <span class="cell-empty" title="{{ $paste->author_ip }}">anonymous</span>
                    @endif
                </td>
                <td>
                    @if ($paste->language)
                        <code>{{ $paste->language }}</code>
                    @else
                        <span class="cell-empty">unknown</span>
                    @endif
                </td>
                <td>{{ format_size($paste->size) }}</td>
                <td>{{ $paste->key ? '✓' : 'x' }}</td>
                <td>
                    <a href="{{ route('show', $paste->slug) }}">show</a> /
                    @php
                    $confirmationString = $paste->id;
                    if ($paste->title !== null) {
                        $confirmationString .= " ($paste->title)";
                    }
                    @endphp
                    <a href="{{ route('admin.remove', $paste->slug) }}" data-confirm="{{ $confirmationString }}">remove</a>
                </td>
                <td>{{ $paste->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </form>
</div>

{{ $pastes->links() }}
@endsection
