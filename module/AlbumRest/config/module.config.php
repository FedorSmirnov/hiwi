<?php
return array (
		
		'controllers' => array (
				
				'invokables' => array (
						
						'AlbumRest\Controller\AlbumRest' => 'AlbumRest\Controller\AlbumRestController' 
				) 
		),
		
		'view_manager' => array (
				
				'template_path_stack' => array (
						
						'album_rest' => __DIR__ . '/../view' 
				) 
		),
		
		'router' => array (
				
				'routes' => array (
						
						'album-rest' => array (
								
								'type' => 'segment',
								'options' => array (
										
										'route' => '/album-rest[/:id]',
										
										'defaults' => array (
												
												'controller' => 'AlbumRest\Controller\AlbumRest' 
										) 
								) 
						) 
				)
				 
		)
		 
);

?>