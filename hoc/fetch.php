<?php

require __DIR__ . '/../src/util.php';

//const ROOT_URL = 'https://byustudies.byu.edu/history-of-the-church';
const ROOT_URL = __DIR__ . '/map.html';
const MAP_LINKS = __DIR__ . '/map-links.json';
const DOMAIN = 'https://byustudies.byu.edu';

function links($dom)
{
    $links = [];
    preg_match_all('|a.*?href="(.*?)".*?>(.*?)</a>|', $dom, $out);
    if (count($out) === 3) {
        for ($i = 0; $i < count($out[0]); $i++) {
            $links[$out[1][$i]] = $out[2][$i];
        }
    }

    return $links;
}

//$map = fetch(ROOT_URL);
//save(__DIR__ . '/map.html', $map);
//$links = links($map);
//save(MAP_LINKS, json_encode($links));

$links = Formigone\Util\fetch(MAP_LINKS);
foreach ($links as $href => $title) {
    if (!preg_match('|^volume (\d+)|i', $title, $vol)) {
        continue;
    }

    $parts = explode('Volume ' . $vol[1], $title);
    $title = trim($parts[1]);
    $dir = __DIR__ . '/volume-' . $vol[1];
    $file = $dir . '/' . $title;

    if (!is_dir($dir)) {
        echo ' >> creating directory [', $dir, ']', PHP_EOL;
        mkdir($dir);
    }

    if (file_exists($file . '.html')) {
        echo ' - skipping file [', $file, ']', PHP_EOL;
        continue;
    }

    $data = Formigone\Util\fetch(DOMAIN . $href);
    Formigone\Util\save($file . '.html', $data);
    echo ' >> saving file [', $file, ']', PHP_EOL;
}
