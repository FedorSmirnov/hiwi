<?php

namespace Apartment;

return array (
		
		'controllers' => array (
				
				'invokables' => array (
						
						'Apartment\Controller\Apartment' => 'Apartment\Controller\ApartmentController',
						'Apartment\Controller\Login' => 'Apartment\Controller\LoginController',
						'Apartment\Controller\Enter' => 'Apartment\Controller\EnterController',
						'Apartment\Controller\EnterLoc' => 'Apartment\Controller\EnterLocController',
						'Apartment\Controller\RoomLoc' => 'Apartment\Controller\RoomLocController' 
				) 
		),
		'view_manager' => array (
				
				'template_path_stack' => array (
						
						'apartment' => __DIR__ . '/../view' 
				) 
		),
		
		'router' => array (
				
				'routes' => array (
						
						'apartment' => array (
								
								'type' => 'segment',
								'options' => array (
										
										'route' => '/apartment[/][:action][/:id]',
										'constraints' => array (
												
												'action' => '[a-zA-Z][a-zA-Z0-9-_]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												
												'controller' => 'Apartment\Controller\Apartment',
												'action' => 'index' 
										) 
								) 
						),
						
						'login' => array (
								
								'type' => 'segment',
								'options' => array (
										
										'route' => '/login',
										
										'defaults' => array (
												
												'controller' => 'Apartment\Controller\Login',
												'action' => 'index' 
										) 
								) 
						),
						
						'enter' => array (
								
								'type' => 'segment',
								'options' => array (
										
										'route' => '/enter[/:action][/:id]',
										
										'constraints' => array (
												
												'id' => '[0-9]+',
												'action' => '[a-zA-Z][a-zA-Z0-9-_]*' 
										),
										
										'defaults' => array (
												
												'controller' => 'Apartment\Controller\Enter',
												'action' => 'index' 
										) 
								) 
						),
						
						'enter-loc' => array (
								
								'type' => 'segment',
								'options' => array (
										
										'route' => '/enter-loc[/:action][/:id]',
										'constraints' => array (
												
												'id' => '[0-9]+',
												'action' => '[a-zA-Z][a-zA-Z0-9-_]*' 
										),
										
										'defaults' => array (
												
												'controller' => 'Apartment\Controller\EnterLoc',
												'action' => 'index' 
										) 
								) 
						),
						
						'room-loc' => array (
								
								'type' => 'segment',
								'options' => array (
										
										'route' => '/room-loc/:apartment/:room[/:action]',
										'constraints' => array (
												
												'apartment' => '[0-9]+',
												'room' => '[a-zA-Z][a-zA-Z0-9-_]*',
												'action' => '[a-zA-Z][a-zA-Z0-9-_]*' 
										),
										
										'defaults' => array (
												
												'controller' => 'Apartment\Controller\RoomLoc',
												'action' => 'index' 
										) 
								) 
						)
						 
				)
				 
		) 
)
;

?>