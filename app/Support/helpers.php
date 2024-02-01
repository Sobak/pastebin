<?php

use Illuminate\Support\HtmlString;

function page_title($title = null)
{
    $output = '';

    if ($title) {
        $output .= "$title / ";
    }

    $output .= config('app.name');

    return $output;
}

function format_size(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, 2) . ' ' . $units[$pow];
}

function render_sortable_table_header(string $columnDisplayName, string $columnDatabaseName): HtmlString
{
    $sortDirection = 'asc';
    $iconClass = '';

    $sortString = request()->query('sort', '');
    $sortParams = explode(':', $sortString);

    $field = $sortParams[0] ?? null;
    $direction = $sortParams[1] ?? null;

    $currentValue = null;
    if ($field === $columnDatabaseName) {
        $currentValue = $direction;
    }

    if ($currentValue === 'asc') {
        $sortDirection = 'desc';
        $iconClass = 'icon-arrow-up';
    } elseif ($currentValue === 'desc') {
        $iconClass = 'icon-arrow-down';
    }

    $string = sprintf(
        '<a href="?sort=%s:%s"><span class="caret icon %s"></span> %s</a>',
        $columnDatabaseName,
        $sortDirection,
        $iconClass,
        $columnDisplayName,
    );

    return new HtmlString($string);
}
