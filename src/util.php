<?php

namespace Formigone\Util;

function fetch($url)
{
    $data = file_get_contents($url);
    if (preg_match('|\.json$|', $url)) {
        $data = json_decode($data, true);
    }

    return $data;
}

function save($filename, $data)
{
    file_put_contents($filename, $data);
}
