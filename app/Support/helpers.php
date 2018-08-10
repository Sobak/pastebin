<?php

function page_title($title = null)
{
    $output = '';

    if ($title) {
        $output .= "$title / ";
    }

    $output .= config('app.name');

    return $output;
}
