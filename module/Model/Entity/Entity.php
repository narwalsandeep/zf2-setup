<?php

namespace Model\Entity;

/**
 *
 * @author Sandeepn
 *        
 */
class Entity {
	
	/**
	 *
	 * @var unknown
	 */
	public $id;
	
	/**
	 *
	 * @param unknown $data        	
	 */
	public function exchangeArray($data) {
		
		// if nothing was sent, do not proceed at all
		if (! $data) {
			return false;
		}
		
		foreach ( $data as $key => $value ) {
			$this->{$key} = $value;
		}
		
		$this->dated = time ();
		return $this;
	}
}
