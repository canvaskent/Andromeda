<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
   protected $tableGateway;

   public function __construct(TableGateway $tableGateway)
   {
	   $this->tableGateway = $tableGateway;
   }

   public function fetchAll()
   {
	   $resultSet = $this->tableGateway->select();
	   return $resultSet;
   }

   public function getAccount( $value, $column = 'id' )
   {
	   $rowset = $this->tableGateway->select(array($column => $value));
	   $row = $rowset->current();
	   if (!$row) {
		   return false;
	   }
	   return $row;
   }
   
   public function changeStatus( $id , $status ){
	   
	   $status ? $status = 1 : $status = 0;
	   
	   return $this->tableGateway->update(array('status'=>$status), array('id' => $id));
	   
   }

   public function saveAccount(Account $account)
   {
	   $data = array(
		   'identity'    => $account->identity,
		   'credential'  => $account->credential,
		   'credential_treatment'  => $account->credential_treatment,
		   'email'       => $account->email,
		   'status'      => $account->status,
	   );

	   $id = (int) $account->id;
	   
	   if ($id == 0) {
		   $this->tableGateway->insert($data);
	   } else {
		   if ($this->getAccount($id)) {
			   $this->tableGateway->update($data, array('id' => $id));
		   } else {
			   throw new \Exception('Album id does not exist');
		   }
	   }
   }

   public function deleteAccount($id)
   {
	   $this->tableGateway->delete(array('id' => (int) $id));
   }
}