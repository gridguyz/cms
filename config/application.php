<?php

use Zend\Stdlib\ArrayUtils;

return ArrayUtils::merge(
    array(
        'modules'   => array(
            'Grid\Core',
            'Grid\Mail',
            'Grid\Customize',
            'Grid\Paragraph',
            'Grid\Tag',
            'Grid\Image',
            'Grid\User',
            'Grid\Menu',
        ),
        'module_listener_options'   => array(
            'config_glob_paths'     => array(
                'config/autoload/{,*.}{global,local}.php',
            ),
            'module_paths'  => array(
                'module',
                'vendor',
            ),
        ),
        'service_manager'   => array(
            'factories'     => array(
                'DbAdapter'             => 'Zork\Db\Adapter\AdapterServiceFactory',
                'ModuleManager'         => 'Zork\Mvc\Service\ModuleManagerFactory',
                'ServiceListener'       => 'Zork\Mvc\Service\ServiceListenerFactory',
            ),
            'invokables'    => array(
                'SiteConfiguration'     => 'Grid\Core\SiteConfiguration\Singlesite',
            ),
            'aliases'       => array(
                'Zork\Db\SiteInfo'                      => 'SiteInfo',
                'Zend\Db\Adapter\Adapter'               => 'DbAdapter',
                'Zork\Db\SiteConfigurationInterface'    => 'SiteConfiguration',
            ),
        ),
    ),
    ArrayUtils::merge(
        require __DIR__ . '/autoload/db.local.php',
        require __DIR__ . '/autoload/application.php'
    )
);
