<?php
/**
 * Register application modules
 */

$application->registerModules([
    'site' => [
        'className' => 'Modules\Site\Module',
        'path' => ROOT_URL . '/apps/modules/site/Module.php'
    ],
    'admin' => [
        'className' => 'Modules\Admin\Module',
        'path' => ROOT_URL . '/apps/modules/admin/Module.php'
    ]
]);
