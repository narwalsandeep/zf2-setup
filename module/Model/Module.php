<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Model;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 *
 * @author sandeepnarwal
 *        
 */
class Module implements AutoloaderProviderInterface {
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
	 */
	public function getAutoloaderConfig() {
		return array (
			'Zend\Loader\StandardAutoloader' => array (
				'namespaces' => array (
					__NAMESPACE__ => __DIR__ 
				) 
			) 
		);
	}
	
	/**
	 *
	 * @return multitype:multitype:NULL |\Model\Entity\UserTable|\Zend\Db\TableGateway\TableGateway
	 */
	public function getServiceConfig() {
		
		// check if directory exists, else installer will give error
		$dir = DOC_ROOT . "\\module\\Model\\Entity\\Generated";
		if (is_dir ( $dir )) {
			$dir = new \DirectoryIterator ( $dir );
			foreach ( $dir as $dirInfo ) {
				if (! $dirInfo->isDot ()) {
					$ClassName = $dirInfo->getFilename ();
					if (! strstr ( $ClassName, "Table" ) && ! strstr ( $ClassName, "Finder" )) {
						$path = "Model\Entity\Generated\\";
						$class = str_replace ( ".php", "", $ClassName );
						
						$entity = $path . $class . 'Table';
						$name = $class;
						$gateway = $path . $class . 'Gateway';
						
						// set table factory
						$factory [$class] = function ($sm) use($name, $entity, $gateway) {
							$tableGateway = $sm->get ( $gateway );
							$table = new $entity ( $tableGateway );
							return $table;
						};
						
						// set gateway factory
						$factory [$gateway] = function ($sm) use($name, $entity, $class, $path) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$initResultSet = new ResultSet ();
							$callee = $path . $class;
							$initResultSet->setArrayObjectPrototype ( new $callee () );
							return new TableGateway ( $name, $dbAdapter, null, $initResultSet );
						};
					}
				}
			}
			return array (
				'factories' => $factory 
			);
		}
	}
	
	/**
	 *
	 * @param MvcEvent $e        	
	 */
	public function onBootstrap(MvcEvent $e) {
	}
}
