<?php
$files = glob('app/Filament/Resources/*Resource.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    // Revert everything back to ?string for a clean slate
    $content = str_replace('protected static string|\UnitEnum|null', 'protected static ?string', $content);
    $content = str_replace('protected static string|\BackedEnum|null', 'protected static ?string', $content);
    
    // Now specifically target each
    $content = str_replace('protected static ?string $navigationIcon', 'protected static string|\BackedEnum|null $navigationIcon', $content);
    $content = str_replace('protected static ?string $navigationGroup', 'protected static string|\UnitEnum|null $navigationGroup', $content);
    // $navigationLabel stays ?string or maybe it needs string|null, but ?string IS string|null.
    
    file_put_contents($file, $content);
}
echo "Fixed properly!";
