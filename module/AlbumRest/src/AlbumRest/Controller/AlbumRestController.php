<?php

namespace AlbumRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Album\Form\AlbumForm;
use Album\Model\Album;
use Zend\View\Model\JsonModel;

class AlbumRestController extends AbstractRestfulController {
	protected $albumTable;
	public function getList() {
		// $results = $this->getAlbumTable ()->fetchAll ();
		// $data = array ();
		// foreach ( $results as $result ) {
		
		// $data [] = $result;
		// }
		
		$json = new JsonModel();
		$content = $json->content;
		
		
		
		return new JsonModel ( array (
				'daten' => $content 
		) );
	}
	public function get($id) {
		// $album = $this->getAlbumTable ()->getAlbum ( $id );
		
		
		$result = 'blabla ' . $id;
		return new JsonModel ( array (
				'daten' => $result 
		) );
	}
	public function create($data) {
		// $form = new AlbumForm ();
		// $album = new Album ();
		// $form->setInputFilter ( $album->getInputFilter () );
		// $form->setData ( $data );
		
		// if ($form->isValid ()) {
		// $album->exchangeArray ( $form->getData () );
		// $id = $this->getAlbumTable ()->saveAlbum ( $album );
		// }
		
		// return new JsonModel ( array (
		
		// 'data' => $this->get ( $id )
		// )
		// );
		$string = $data['content'];
		
		
		return new JsonModel(array("content"=>$string));
	}
	public function update($id, $data) {
	}
	public function delete($id) {
	}
	public function getAlbumTable() {
		if (! $this->albumTable) {
			$sm = $this->getServiceLocator ();
			$this->albumTable = $sm->get ( 'Album\Model\AlbumTable' );
		}
		return $this->albumTable;
	}
}

?>