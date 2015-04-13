<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * 
 * @author Sandeepn
 *
 */
class IndexController extends AbstractActionController
{
    /**
     * (non-PHPdoc)
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
	public function indexAction()
    {
		//die('landing page');
        $this->redirect()->toRoute('auth', array(
            'controller' => 'login'
        ));
    }
}
