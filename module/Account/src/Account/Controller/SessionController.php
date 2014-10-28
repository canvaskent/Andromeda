<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Account\Form;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result;

use Zend\Authentication\Storage\Session as SessionStorage;

class SessionController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function loginAction()
    {
		$auth = new AuthenticationService();
		
		$code = 'NONE';
		
		if ($auth->hasIdentity()) {
			// Identity exists; get it
			$form = false;
			$identity = $auth->getIdentity();
			
		}else{
			
			$identity = false;
		
			$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			
			$form = new Form\LoginForm($adapter);
			
			$request = $this->getRequest();
	
			if ($request->isPost()) {
				
			   $form->setData($request->getPost());
			   
			   if ($form->isValid()){
				   
				   $data = $form->getData();
	
				   $authAdapter = new AuthAdapter($adapter);
				  
				   $authAdapter
					  ->setTableName('account')
					  ->setIdentityColumn('email')
					  ->setCredentialColumn('credential_treatment')
					  ->setCredentialTreatment('MD5(?)')
					  ->getDbSelect()->where('status=1');
	
				   $authAdapter
					  ->setIdentity($data['email'])
					  ->setCredential($data['credential']);
					  
				   $result = $auth->authenticate($authAdapter);
				   $code = $result->getCode();
	
					switch ($code) {
	
						case Result::SUCCESS:
							/** do stuff for successful authentication **/
							$code = 'SUCCESS';
							break;
	
						case Result::FAILURE_IDENTITY_NOT_FOUND:
							/** do stuff for nonexistent identity **/
							$code = 'FAILURE_IDENTITY_NOT_FOUND';
							break;
					
						case Result::FAILURE_CREDENTIAL_INVALID:
							/** do stuff for invalid credential **/
							$code = 'FAILURE_CREDENTIAL_INVALID';
							break;
	
						default:
							/** do stuff for other failure **/
							$code = 'FAILURE';
							break;
					}
				   
			   }
			   
			}

		}
		
        return new ViewModel(array(
		                'form'=>$form,
						'code'=>$code,
						'identity'=>$identity,
		));
    }

    public function logoutAction()
    {
		$auth = new AuthenticationService();
		$auth->clearIdentity();
		
		if ($auth->hasIdentity()) {
			// Identity exists; get it
			$identity = $auth->getIdentity();
			
		}else{
			
			$identity = false;
			
		}
		
        return new ViewModel(array('identity'=>$identity));
    }

	public function captchaAction(){
		
        $response = $this->getResponse();
        $id = $this->params('fileid',false);
		
 
        if ($id) {
 
            $image = './data/captcha/images/' . $id;

            if (file_exists($image) !== false) {
				
                $imagegetcontent = @file_get_contents($image);
				$response->getHeaders()->addHeaderLine('Content-Type', "image/png");
                $response->setStatusCode(200);
                $response->setContent($imagegetcontent);
 
                if (file_exists($image) == true) {
                    unlink($image);
                }
				
            }else{
				return new ViewModel(array('id'=>$id));
			}
 
        }else{
				return new ViewModel(array('id'=>$id));
			}
 
        return $response;

	}

}

