<?php
// Script to remove full-line single-line comments (//) from project files
$dirs = ['src', 'config', 'templates', 'tests'];
$extensions = ['php', 'yaml', 'yml', 'twig'];
$exclude = ['vendor', 'var', 'migrations'];

function shouldProcess($file)
{
    global $exclude, $extensions;
    foreach ($exclude as $ex) {
        if (strpos($file, DIRECTORY_SEPARATOR . $ex . DIRECTORY_SEPARATOR) !== false) return false;
    }
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    return in_array(strtolower($ext), $extensions);
}

$files = [];
foreach ($dirs as $dir) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $f) {
        if (!$f->isFile()) continue;
        $path = $f->getPathname();
        if (shouldProcess($path)) $files[] = $path;
    }
}

foreach ($files as $file) {
    $lines = file($file);
    $changed = false;
    $out = [];
    foreach ($lines as $line) {
        if (preg_match('/^\s*\/\//', $line)) {
            $changed = true;
            continue;
        }
        $out[] = $line;
    }
    if ($changed) {
        file_put_contents($file, implode('', $out));
        echo "Stripped comments from: $file\n";
    }
}
