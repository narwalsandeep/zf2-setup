<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Crypt\Password\Bcrypt;

/**
 *
 * @author Sandeepn
 *        
 */
class SigninController extends \Core\System\BaseSystem {
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		$this->db ()->table ( "DemoUser" );
		$this->db ()->finder ( "DemoUser" );
		
	}
	
	/**
	 */
	public function processAction() {
		$params = $this->params ()->fromPost ();
	}
}
