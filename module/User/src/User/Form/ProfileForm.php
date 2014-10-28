<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha;
use Zend\InputFilter\InputFilter;
use Zend\Captcha\Image as CaptchaImage;

class ProfileForm extends Form
{
   public function __construct( $dbAdapter )
   {

	   parent::__construct('profile');
	   
	   $this->add(array(
		   'name' => 'nickname',
		   'type' => 'Text'
	   ));
	   
	   $this->add(array(
		   'name' => 'sex',
		   'type' => 'Select',
		   'options' => array(
				   'value_options' => array(
						   '0' => '',
						   '1' => '',
				   ),
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
		   'name'     => 'nickname',
		   'required' => true,
		   'validators' => array(
			   array(
				   'name'    => 'StringLength',
				   'options' => array(
					   'encoding' => 'UTF-8',
					   'min'      => 2,
					   'max'      => 18,
				   ),
			   ),
			   array(
				   'name'    => 'Regex',
				   'options' => array(
					   'pattern' => '/^[\\S]+$/u',
                    ),
			   ),
			   array(
				   'name'    => 'Db\NoRecordExists',
				   'options' => array(
						'table'   => 'user',
						'field'   => 'nickname',
						'adapter' => $dbAdapter
                    ),
			   ),
		   ),
	   ));


	   $inputFilter->add(array(
		   'name'     => 'sex',
		   'required' => true,
		   'validators' => array(
			   array(
				   'name'    => 'Regex',
				   'options' => array(
					   'pattern' => '/^[01]{1}$/u',
                    ),
			   ),
		   ),
	   ));

	   $this->setInputFilter($inputFilter);

   }

}