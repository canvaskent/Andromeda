<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;

use User\Form;

class PeopleController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function profileAction()
    {
		$form = NULL;
		
		$auth = new AuthenticationService();
		
		if ($auth->hasIdentity()) {
		
			$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$form = new Form\ProfileForm($adapter);
			
			$request = $this->getRequest();
	
			if ($request->isPost()) {
				
			   $form->setData($request->getPost());
			   
			   if ($form->isValid()){
				   
				   $data = $form->getData();
				   
			   }
			   
			}else{
				
				//get user profile form database.
				
			}
		
		}
		
        return new ViewModel(array('form'=>$form));
    }


}

