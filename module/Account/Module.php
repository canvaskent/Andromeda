<?php
namespace Account;

use Account\Model\Account;
use Account\Model\AccountTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

	public function getServiceConfig()
	{
	   return array(
		   'factories' => array(
			   'Account\Model\AccountTable' =>  function($sm) {
				   $tableGateway = $sm->get('AccountTableGateway');
				   $table = new AccountTable($tableGateway);
				   return $table;
			   },
			   'AccountTableGateway' => function ($sm) {
				   $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				   $resultSetPrototype = new ResultSet();
				   $resultSetPrototype->setArrayObjectPrototype(new Account());
				   return new TableGateway('account', $dbAdapter, null, $resultSetPrototype);
			   },
		   ),
	   );
	}

}
