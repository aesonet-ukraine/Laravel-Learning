<?php

declare(strict_types=1);

$ignoreErrors = [];
$ignoreErrors[] = [
    'message' => '#^Access to an undefined property App\\\\Http\\\\Resources\\\\V1\\\\Admin\\\\ProductResource\\:\\:\\$finalPrice\\.$#',
    'identifier' => 'property.notFound',
    'count' => 1,
    'path' => __DIR__.'/app/Http/Resources/V1/Admin/ProductResource.php',
];
$ignoreErrors[] = [
    'message' => '#^Access to an undefined property App\\\\Http\\\\Resources\\\\V1\\\\Admin\\\\ProductResource\\:\\:\\$thumbnailUrl\\.$#',
    'identifier' => 'property.notFound',
    'count' => 1,
    'path' => __DIR__.'/app/Http/Resources/V1/Admin/ProductResource.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
