<?php
return array(

    'router' => array(
        'routes' => array(
            'people' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/people/:id',
					'constraints' => array(
						'id' => '[a-zA-Z0-9_-]+',
					),
                    'defaults' => array(
                        'controller' => 'User\Controller\People',
                        'action'     => 'index',
                    ),
                ),
			),
			'profile' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/people/profile',
					'defaults' => array(
                        'controller' => 'User\Controller\People',
						'action'     => 'profile',
					),
				),
			),

		),
	),

    'controllers' => array(
        'invokables' => array(
            'User\Controller\People' => 'User\Controller\PeopleController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);