<?php
/**
 * Register application modules
 */

$application->registerModules([
    'site' => [
        'className' => 'Modules\Site\Module',
        'path' => APP_URL . 'modules/site/Module.php'
    ],
    'admin' => [
        'className' => 'Modules\Admin\Module',
        'path' => APP_URL . 'modules/admin/Module.php'
    ]
]);
