
<?php
$File = __DIR__ . '/vendor/composer/installed.json';

if (!file_exists($File)) {
    exit("File installed.json tidak ditemukan.\n");
}

$Data = json_decode(file_get_contents($File), true);

if (!is_array($Data)) {
    exit("Format installed.json tidak valid.\n");
}

$Packages = $Data['packages'] ?? $Data;
$Result = [];

foreach ($Packages as $Package) {
    if (empty($Package['name'])) {
        continue;
    }

    $Result[] = [
        'name' => $Package['name'],
        'version' => $Package['pretty_version']
            ?? $Package['version']
            ?? 'unknown',
        'type' => $Package['type'] ?? 'library',
        'require' => $Package['require'] ?? new stdClass(),
        'autoload' => $Package['autoload'] ?? new stdClass(),
        'dev_requirement' => $Package['dev_requirement'] ?? false
    ];
}

usort($Result, fn($a, $b) => strcmp($a['name'], $b['name']));

file_put_contents(
    __DIR__ . '/composer_inventory.json',
    json_encode($Result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Berhasil menginventarisasi " . count($Result) . " package.\n";
echo "File: composer_inventory.json\n";
