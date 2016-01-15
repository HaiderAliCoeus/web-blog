<?php

namespace Test;

return array(
     'controllers' => array(
         'invokables' => array(
             'Test\Controller\Test' => 'Test\Controller\TestController',
         ),
     ),
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'test' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/test[/][:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Test\Controller\Test',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'test' => __DIR__ . '/../view',
         ),
     ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
 );
