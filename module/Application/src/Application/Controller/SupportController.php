<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 * @author Sandeepn
 *        
 */
class SupportController extends AbstractActionController {
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function contactUsAction() {
		return new ViewModel ( array () );
	}
}
