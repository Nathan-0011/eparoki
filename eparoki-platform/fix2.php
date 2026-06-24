<?php
$files = glob('app/Filament/Resources/*Resource.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    $content = str_replace('protected static string|\BackedEnum|null', 'protected static string|\UnitEnum|null', $content);
    file_put_contents($file, $content);
}
echo "Fixed to UnitEnum!";
