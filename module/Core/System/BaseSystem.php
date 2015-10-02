<?php

namespace Core\System;

use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 * @author Sandeepn
 *        
 */
class BaseSystem extends AbstractActionController {
	
	/**
	 *
	 * @param unknown $service        	
	 * @return \Core\System\BaseDb
	 */
	protected function db() {
		return new BaseDb ( $this->getServiceLocator () );
	}
}
