<?php
namespace Account\Model;

class Account
{
   public $id;
   public $identity;
   public $credential;
   public $credential_treatment;
   public $email;
   public $status;

   public function exchangeArray($data)
   {
	   $this->id                   = (!empty($data['id']))                   ? $data['id']                   : null;
	   $this->identity             = (!empty($data['identity']))             ? $data['identity']             : null;
	   $this->credential           = (!empty($data['credential']))           ? $data['credential']           : null;
	   $this->credential_treatment = (!empty($data['credential_treatment'])) ? $data['credential_treatment'] : md5($data['credential']);
	   $this->email                = (!empty($data['email']))                ? $data['email']                : null;
	   $this->status               = (!empty($data['status']))               ? $data['status']               : 0;
   }

   public function getArrayCopy(){
	   return array(
		   'id' => $this->id,
		   'identity' => $this->identity,
		   'credential' => $this->credential,
		   'credential_treatment' => $this->credential_treatment,
		   'email' => $this->email,
		   'status' => $this->status,
	   );
   }
}