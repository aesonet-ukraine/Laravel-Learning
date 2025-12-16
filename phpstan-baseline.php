<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Category\\:\\:children\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\HasMany does not specify its types\\: TRelatedModel, TDeclaringModel$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Category\\:\\:parent\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsTo does not specify its types\\: TRelatedModel, TDeclaringModel$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Category\\:\\:products\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany does not specify its types\\: TRelatedModel, TDeclaringModel, TPivotModel, TAccessor \\(2\\-4 required\\)$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Image\\:\\:imageable\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\MorphTo does not specify its types\\: TRelatedModel, TDeclaringModel$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Image.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Order\\:\\:getUserOrders\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\HasMany does not specify its types\\: TRelatedModel, TDeclaringModel$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Order.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Order\\:\\:products\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\HasMany does not specify its types\\: TRelatedModel, TDeclaringModel$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Order.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Product\\:\\:categories\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany does not specify its types\\: TRelatedModel, TDeclaringModel, TPivotModel, TAccessor \\(2\\-4 required\\)$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Product.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Models\\\\Product\\:\\:orders\\(\\) return type with generic class Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\BelongsToMany does not specify its types\\: TRelatedModel, TDeclaringModel, TPivotModel, TAccessor \\(2\\-4 required\\)$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/app/Models/Product.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$startingValue of method Illuminate\\\\Database\\\\Schema\\\\ColumnDefinition\\:\\:startingValue\\(\\) expects int, float given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/database/migrations/2025_12_09_160244_create_products_table.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Database\\\\Factories\\\\CategoryFactory\\:\\:hasProducts\\(\\)\\.$#',
	'identifier' => 'method.notFound',
	'count' => 2,
	'path' => __DIR__ . '/database/seeders/CategoriesAndProductsSeeder.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Database\\\\Seeders\\\\OnDemand\\\\PermissionsAndRolesSeeder\\:\\:createRoleWithPermission\\(\\) has parameter \\$permissions with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/database/seeders/OnDemand/PermissionsAndRolesSeeder.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Database\\\\Seeders\\\\OnDemand\\\\PermissionsAndRolesSeeder\\:\\:deleteRoleWithPermission\\(\\) has parameter \\$permissions with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/database/seeders/OnDemand/PermissionsAndRolesSeeder.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
