<?php
/**
 * Register application modules
 */

$application->registerModules(array(
    'site' => array(
        'className' => 'Modules\Site\Module',
        'path' => ROOT_URL . '/apps/modules/site/Module.php'
    ),
    'admin' => array(
        'className' => 'Modules\Admin\Module',
        'path' => ROOT_URL . '/apps/modules/admin/Module.php'
    )
));
