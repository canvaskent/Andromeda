<?php
return array(
    'router' => array(
        'routes' => array(
            'register' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/register',
                    'defaults' => array(
                        'controller' => 'Account\Controller\Account',
                        'action'     => 'register',
                    ),
                ),
				'may_terminate' => true,
				'child_routes' => array(
					'checkemail' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/checkemail/:email',
                            'constraints' => array(
                                'email' => '.+',
                            ),
							'defaults' => array(
								'action'     => 'checkemail',
							),
						),
					),
					'active' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/active/:accountid',
                            'constraints' => array(
                                'accountid' => '.+',
                            ),
							'defaults' => array(
								'action'     => 'active',
							),
						),
					),
				),
			),
            'session' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/session',
                    'defaults' => array(
                        'controller' => 'Account\Controller\Session',
                        'action'     => 'login',
                    ),
                ),
				'may_terminate' => true,
				'child_routes' => array(
					'captcha' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/captcha/:fileid',
                            'constraints' => array(
                                'fileid' => '.+',
                            ),
							'defaults' => array(
								'action'     => 'captcha',
							),
						),
					),
					'logout' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/logout',
							'defaults' => array(
								'action'     => 'logout',
							),
						),
					),
				),
			),
		),
	),
    'controllers' => array(
        'invokables' => array(
            'Account\Controller\Account' => 'Account\Controller\AccountController',
			'Account\Controller\Session' => 'Account\Controller\SessionController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);