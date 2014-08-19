<?php
namespace Account\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha;
use Zend\InputFilter\InputFilter;
use Zend\Captcha\Image as CaptchaImage;

class RegisterForm extends Form
{
   public function __construct( $dbAdapter )
   {

	   parent::__construct('register');
	   
	   $this->add(array(
		   'name' => 'identity',
		   'type' => 'Text'
	   ));
	   
	   $this->add(array(
		   'name' => 'email',
		   'type' => 'Email'
	   ));

	   $this->add(array(
		   'name' => 'credential',
		   'type' => 'Text'
	   ));

	   $this->add(array(
		   'name' => 'credential_confirm',
		   'type' => 'Text'
	   ));



        $captchaImage = new CaptchaImage(  array(
                'font' => './data/captcha/fonts/arial.ttf',
                'width' => 130,
                'height' => 40,
				'fontSize' => 15,
                'dotNoiseLevel' => 3,
                'lineNoiseLevel' => 1
				)
        );
        $captchaImage->setImgDir('./data/captcha/images');
        $captchaImage->setImgUrl('/session/captcha');

	  $this->add(array(
		  'type' => 'Captcha',
		  'name' => 'captcha',
		  'options' => array(
			  'label' => 'Please verify you are human',
			  'captcha' => $captchaImage,
		  ),
	  ));


	   $this->add(array(
		   'name' => 'submit',
		   'type' => 'Submit',
		   'attributes' => array(
			   'id' => 'submitbutton',
		   ),
	   ));
	   
	   // add input filters
	   $inputFilter = new InputFilter();

	   $inputFilter->add(array(
		   'name'     => 'identity',
		   'required' => true,
		   'validators' => array(
			   array(
				   'name'    => 'StringLength',
				   'options' => array(
					   'encoding' => 'UTF-8',
					   'min'      => 6,
					   'max'      => 18,
				   ),
			   ),
			   array(
				   'name'    => 'Alnum',
				   'options' => array(
					   'allowWhiteSpace' => false,
                    ),
			   ),
			   array(
				   'name'    => 'Regex',
				   'options' => array(
					   'pattern' => '/^[a-z]+[0-9a-z]*$/u',
                    ),
			   ),
			   array(
				   'name'    => 'Db\NoRecordExists',
				   'options' => array(
						'table'   => 'account',
						'field'   => 'identity',
						'adapter' => $dbAdapter
                    ),
			   ),
		   ),
	   ));

	   $inputFilter->add(array(
		   'name'     => 'email',
		   'required' => true,
		   'validators'  => array(
			   array('name' => 'EmailAddress'),
			   array(
				   'name'    => 'Db\NoRecordExists',
				   'options' => array(
						'table'   => 'account',
						'field'   => 'email',
						'adapter' => $dbAdapter
                    ),
			   ),
		   ),
	   ));

	   $inputFilter->add(array(
		   'name'     => 'credential',
		   'required' => true,
		   'validators'  => array(
			   array(
				   'name'    => 'StringLength',
				   'options' => array(
					   'encoding' => 'UTF-8',
					   'min'      => 8,
					   'max'      => 20,
				   ),
			   ),
			   array(
				   'name'    => 'Regex',
				   'options' => array(
					   'pattern' => '/^[\\x{21}-\\x{7E}]+$/u',
                    ),
			   ),
		   ),
	   ));

	   $inputFilter->add(array(
		   'name'     => 'credential_confirm',
		   'required' => true,
		   'validators'  => array(
				array(
					'name'    => 'Identical',
					'options' => array(
						'token' => 'credential',
					),
				),
		   ),
	   ));

	   $this->setInputFilter($inputFilter);

   }

}