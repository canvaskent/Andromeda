<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

return array(
     'db' => array(
         'driver'         => 'Pdo',
         'driver_options' => array(
             PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
         ),
     ),
     'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
			 'SmtpTransport' => function ($sm){
					$transport = new SmtpTransport();
					$options   = new SmtpOptions(array(
						'name'              => 'smtp.exmail.qq.com',
						'host'              => 'smtp.exmail.qq.com',
						'port' => 465,
						'connection_class'  => 'login',
						'connection_config' => array(
							'username' => 'system@solody.com',
							'password' => 'system123#@!',
							'ssl' => 'ssl',
						),
					));
					$transport->setOptions($options);
					return $transport;
			 }
         ),
     ),
);
