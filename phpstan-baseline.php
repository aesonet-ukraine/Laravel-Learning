<?php

declare(strict_types=1);

$ignoreErrors = [];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Http\\\\Controllers\\\\Api\\\\AuthController\\:\\:login\\(\\) has no return type specified\\.$#',
    'identifier' => 'missingType.return',
    'count' => 1,
    'path' => __DIR__.'/app/Http/Controllers/Api/AuthController.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Http\\\\Controllers\\\\Api\\\\AuthController\\:\\:register\\(\\) has no return type specified\\.$#',
    'identifier' => 'missingType.return',
    'count' => 1,
    'path' => __DIR__.'/app/Http/Controllers/Api/AuthController.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Category\\:\\:children\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\HasMany does not specify its types\\: TRelatedModel, TDeclaringModel$#',
    'identifier' => 'missingType.generics',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Category.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Category\\:\\:parent\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsTo does not specify its types\\: TRelatedModel, TDeclaringModel$#',
    'identifier' => 'missingType.generics',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Category.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Category\\:\\:products\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany does not specify its types\\: TRelatedModel, TDeclaringModel, TPivotModel, TAccessor \\(2\\-4 required\\)$#',
    'identifier' => 'missingType.generics',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Category.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Image\\:\\:imageable\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\MorphTo does not specify its types\\: TRelatedModel, TDeclaringModel$#',
    'identifier' => 'missingType.generics',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Image.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Order\\:\\:getUserOrders\\(\\) should return Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany\\<App\\\\Models\\\\User, App\\\\Models\\\\Order, Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot, \'pivot\'\\> but returns Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany\\<App\\\\Models\\\\User, \\$this\\(App\\\\Models\\\\Order\\), Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot, \'pivot\'\\>\\.$#',
    'identifier' => 'return.type',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Order.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Order\\:\\:products\\(\\) should return Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany\\<App\\\\Models\\\\Product, App\\\\Models\\\\Order, Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot, \'pivot\'\\> but returns Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany\\<App\\\\Models\\\\Product, \\$this\\(App\\\\Models\\\\Order\\), Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot, \'pivot\'\\>\\.$#',
    'identifier' => 'return.type',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Order.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Product\\:\\:categories\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany does not specify its types\\: TRelatedModel, TDeclaringModel, TPivotModel, TAccessor \\(2\\-4 required\\)$#',
    'identifier' => 'missingType.generics',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Product.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\Product\\:\\:orders\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany does not specify its types\\: TRelatedModel, TDeclaringModel, TPivotModel, TAccessor \\(2\\-4 required\\)$#',
    'identifier' => 'missingType.generics',
    'count' => 1,
    'path' => __DIR__.'/app/Models/Product.php',
];
$ignoreErrors[] = [
    'message' => '#^Method App\\\\Models\\\\User\\:\\:orders\\(\\) should return Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\HasMany\\<App\\\\Models\\\\Order, App\\\\Models\\\\User\\> but returns Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\HasMany\\<App\\\\Models\\\\Order, \\$this\\(App\\\\Models\\\\User\\)\\>\\.$#',
    'identifier' => 'return.type',
    'count' => 1,
    'path' => __DIR__.'/app/Models/User.php',
];
$ignoreErrors[] = [
    'message' => '#^Call to an undefined method Database\\\\Factories\\\\CategoryFactory\\:\\:hasProducts\\(\\)\\.$#',
    'identifier' => 'method.notFound',
    'count' => 2,
    'path' => __DIR__.'/database/seeders/CategoriesAndProductsSeeder.php',
];
$ignoreErrors[] = [
    'message' => '#^Method Database\\\\Seeders\\\\OnDemand\\\\PermissionsAndRolesSeeder\\:\\:createRoleWithPermission\\(\\) has parameter \\$permissions with no value type specified in iterable type array\\.$#',
    'identifier' => 'missingType.iterableValue',
    'count' => 1,
    'path' => __DIR__.'/database/seeders/OnDemand/PermissionsAndRolesSeeder.php',
];
$ignoreErrors[] = [
    'message' => '#^Method Database\\\\Seeders\\\\OnDemand\\\\PermissionsAndRolesSeeder\\:\\:deleteRoleWithPermission\\(\\) has parameter \\$permissions with no value type specified in iterable type array\\.$#',
    'identifier' => 'missingType.iterableValue',
    'count' => 1,
    'path' => __DIR__.'/database/seeders/OnDemand/PermissionsAndRolesSeeder.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
