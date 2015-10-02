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
		
		// below is how you get all params
		// e.g $params['username']
		$params = $this->params ()->fromPost ();
		
		/*
		 * NOT SIGNIN LOGIC IS ADDED, YOU MUST ADD YOU SELF HERE
		 *
		 */
		
		// return JSON because you used flyjax to submit here !!
		$view = new \Zend\View\Model\JsonModel ( array (
			"success" => true,
			"message" => "Woohoo... it worked !!" 
		) );
		return $view;
	}
}
