<?php

namespace Apartment;

use Apartment\Model\Apartment;
use Apartment\Model\ApartmentTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Apartment\Model\UserTable;
use Apartment\Model\User;
use Apartment\Model\RoomTable;
use Apartment\Model\Room;

class Module {
	public function getAutoloaderConfig() {
		return array (
				
				'Zend\Loader\ClassMapAutoloader' => array (
						
						__DIR__ . '/autoload_classmap.php' 
				),
				
				'Zend\Loader\StandardAutoloader' => array (
						
						'namespaces' => array (
								
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getServiceConfig() {
		return array (
				
				'factories' => array (
						
						'Apartment\Model\ApartmentTable' => function ($sm) {
							
							$tableGateway = $sm->get ( 'ApartmentTableGateway' );
							$table = new ApartmentTable ( $tableGateway );
							return $table;
						},
						'ApartmentTableGateway' => function ($sm) {
							
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Apartment () );
							return new TableGateway ( 'apartments', $dbAdapter, null, $resultSetPrototype );
						},
						'Apartment\Model\UserTable' => function ($sm) {
							
							$tableGateway = $sm->get ( 'UserTableGateway' );
							$table = new UserTable ( $tableGateway );
							return $table;
						},
						'UserTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new User () );
							return new TableGateway ( 'users', $dbAdapter, null, $resultSetPrototype );
						},
						'Apartment\Model\RoomTable' => function ($sm) {
							$tableGateway = $sm->get ( 'RoomTableGateway' );
							$table = new RoomTable ( $tableGateway );
							return $table;
						},
						'RoomTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Room () );
							return new TableGateway ( 'rooms', $dbAdapter, null, $resultSetPrototype );
						} 
				) 
		);
	}
}

?>