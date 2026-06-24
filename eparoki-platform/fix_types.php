<?php
$files = glob('app/Filament/Resources/*Resource.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    $content = str_replace('protected static ?string $navigationIcon', 'protected static string|\BackedEnum|null $navigationIcon', $content);
    $content = str_replace('protected static ?string $navigationGroup', 'protected static string|\BackedEnum|null $navigationGroup', $content);
    $content = preg_replace('/protected static \?string \$navigationIcon/', 'protected static ?string $navigationIcon', $content); // wait, backed enum? No, UnitEnum!
    $content = str_replace('protected static ?string $navigationGroup', 'protected static string|\UnitEnum|null $navigationGroup', $content);
    $content = str_replace('protected static ?string $navigationIcon', 'protected static string|\UnitEnum|null $navigationIcon', $content);
    $content = str_replace('protected static ?string $navigationLabel', 'protected static string|\UnitEnum|null $navigationLabel', $content);
    file_put_contents($file, $content);
}
echo "Fixed types!";
