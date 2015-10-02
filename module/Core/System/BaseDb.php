<?php

namespace Core\System;

use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 * @author Sandeepn
 *        
 */
class BaseDb extends BaseSystem {
	
	/**
	 *
	 * @param unknown $sm        	
	 */
	public function __construct($sm) {
		$this->sm = $sm;
	}
	
	/**
	 *
	 * @param unknown $service        	
	 * @return Ambigous <object, multitype:>
	 */
	public function table($service) {
		return $this->sm->get ( $service );
	}
	
	/**
	 *
	 * @param unknown $service        	
	 */
	public function finder($service) {
		return $this->sm->get ( $service )->getFinder ();
	}
	
	/**
	 */
	public function run() {
		
		// execute raw query here .. DIY
	}
}
