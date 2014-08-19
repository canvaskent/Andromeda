<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Account\Form;
use Account\Model;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class AccountController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function registerAction()
    {
		$form = new Form\RegisterForm('/register/captcha');
		$account = new Model\Account();
		$form->bind($account);
		
        $request = $this->getRequest();

		if ($request->isPost()) {
			
		   $form->setData($request->getPost());
		   
		   if ($form->isValid()){
			   
			   $data = $form->getData();
			   
			   // save the account
			   $table = $this->getAccountTable();
			   $table->saveAccount($data);
			   
			   // send active email
			   $smtp = $this->getServiceLocator()->get('SmtpTransport');
			   $translator = $this->getServiceLocator()->get('translator');
			   $config = $this->getServiceLocator()->get('Config');
			   
			   $active_key = base64_encode($data->email);
			   $active_url = 'http://'.$config['siteinfo']['domain'].$this->url()->fromRoute('register/active', array('accountid'=>$active_key));
			   
			   $email_html = new MimePart( sprintf($translator->translate('Last step to be a solody member! Just click the link below to active and login solody.com :<br> <a href="%s" title="Active your account !" target="_blank">%s</a>'),$active_url,$active_url) );
			   $email_html->type = "text/html";
			   
			   $email_body = new MimeMessage();
			   $email_body->setParts(array($email_html));
			   
               $message = new Message();
               $message->addTo( $data->email )
                       ->addFrom( 'system@solody.com',$translator->translate('SOLODY MASTER') )
                       ->setSubject( $translator->translate('[Solody.com] Welcome for register solody.com, this email will guide you to active your account.') )
                       ->setBody( $email_body );
						
               $smtp->send($message);
			   
			   $this->Redirect()->toRoute('register/checkemail',array('email'=>base64_encode($data->email)));
		   }
		   
		}
		
        return new ViewModel(array('form'=>$form));
    }
	
	public function checkemailAction(){

        $response = $this->getResponse();
        $email = base64_decode($this->params('email',false));

		return new ViewModel(array('email'=>$email));
		
	}
	
	public function activeAction(){
		
        $response = $this->getResponse();
        $email = base64_decode($this->params('accountid',false));
		
		$table = $this->getAccountTable();
		$row = $table->getAccount($email,'email');
		
		$actived = false;
		
		if ($row){
			
			if ($table->changeStatus((int)$row->id,true)) $actived = true;
			
		}
		
		return new ViewModel(array(
		                 'email' =>$email,
		                 'actived'=>$actived,
						 ));
		
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

	protected function getAccountTable()
	{
	   $sm = $this->getServiceLocator();
	   return $sm->get('Account\Model\AccountTable');
	}

}

