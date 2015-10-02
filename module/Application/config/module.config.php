<?php
return array (
	'router' => array (
		'routes' => array (
			'home' => array (
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array (
					'route' => '/',
					'defaults' => array (
						'controller' => 'Application\Controller\Index',
						'action' => 'index' 
					) 
				) 
			),
			'application' => array (
				'type' => 'Segment',
				'options' => array (
					'route' => '/application[/:controller[/:action[/:id]]]',
					'constraints' => array (
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
					),
					'defaults' => array (
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'index',
						'action' => 'index' 
					) 
				) 
			) 
		) 
	),
	'service_manager' => array (
		'abstract_factories' => array (
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory' 
		),
		'aliases' => array (
			'translator' => 'MvcTranslator' 
		) 
	),
	'translator' => array (
		'locale' => 'en_US',
		'translation_file_patterns' => array (
			array (
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern' => '%s.mo' 
			) 
		) 
	),
	'controllers' => array (
		'invokables' => array (
			'Application\Controller\Index' => 'Application\Controller\IndexController',
			'Application\Controller\Signup' => 'Application\Controller\SignupController',
			'Application\Controller\Signin' => 'Application\Controller\SigninController',
			'Application\Controller\Installer' => 'Application\Controller\InstallerController',
			'Application\Controller\Support' => 'Application\Controller\SupportController' 
		) 
	),
	'view_manager' => array (
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array (
			'application/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/404-friendly' => __DIR__ . '/../view/error/404-friendly.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
			'flash-messages' => __DIR__ . '/../view/layout/flash-messages.phtml' 
		),
		'template_path_stack' => array (
			__DIR__ . '/../view' 
		),
		'strategies' => array (
			'ViewJsonStrategy' 
		) 
	),
	// Placeholder for console routes
	'console' => array (
		'router' => array (
			'routes' => array () 
		) 
	) 
);
