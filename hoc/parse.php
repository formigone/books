<?php

require __DIR__ . '/../src/util.php';

const HOOK = 'node-history-of-the-church';

$dirs = array_filter(scandir(__DIR__), function (&$path) {
    if (preg_match('|^volume|', $path)) {
        $path = __DIR__ . '/' . $path;
        return true;
    }
});


foreach ($dirs as $dir) {
    echo 'Parsing [', $dir, ']', PHP_EOL;

    array_map(function ($path) use ($dir) {
        $file = $dir . '/' . $path;
        $dom = Formigone\Util\fetch($file);
        if (strlen($dom) === 0) {
            return;
        }

        echo ' >> [', $file, ']', PHP_EOL;
        $subDom = explode(HOOK, $dom);
        if (count($subDom) === 1) {
            echo ' - Could not parse [', $file, ']', PHP_EOL;
            return;
        }

        $subDom = strstr($subDom[1], '</div>', true);
        $subDom = strstr($subDom, '>');
        $subDom = strstr($subDom, '<');

        if (!$subDom) {
            echo ' - Could not save [', $file, ']', PHP_EOL;
            return;
        }

        Formigone\Util\save($file, $subDom);
    }, scandir($dir));
}
