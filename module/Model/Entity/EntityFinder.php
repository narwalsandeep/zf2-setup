<?php

namespace Model\Entity;

use Zend\Db\TableGateway\TableGateway;

class EntityFinder {
	
	/**
	 *
	 * @var unknown
	 */
	protected $_params = array ();
	
	/**
	 *
	 * @var unknown
	 */
	protected $_paginate;
	
	/**
	 *
	 * @var unknown
	 */
	protected $_current_page;
	
	/**
	 *
	 * @var unknown
	 */
	protected $_items_per_page;
	
	/**
	 *
	 * @var unknown
	 */
	protected $_return_type;
	
	/**
	 *
	 * @var EntityTable
	 */
	protected $_table;
	
	/**
	 *
	 * @param EntityTable $table        	
	 */
	public function __construct(EntityTable $table) {
		$this->_table = $table;
	}
	
	/**
	 *
	 * @param string $params        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setParams($params = null) {
		$this->_params = array_merge ( $this->_params, $params );
		return $this;
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function getParams() {
		return $this->_params;
	}
	
	/**
	 *
	 * @return \Model\Entity\EntityFinder
	 */
	public function clearParams() {
		$this->_params = null;
		return $this;
	}
	
	/**
	 *
	 * @param string $flag        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setPagination($flag = false) {
		$this->_paginate = $flag;
		return $this;
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function getPagination() {
		return $this->_paginate;
	}
	
	/**
	 *
	 * @param unknown $page        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setCurrentPage($page) {
		$this->_current_page = $page;
		return $this;
	}
	
	/**
	 *
	 * @param unknown $itemsPerPage        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setItemsPerPage($itemsPerPage) {
		$this->_items_per_page = $itemsPerPage;
		return $this;
	}
	
	/**
	 */
	public function getSql() {
		return $this->_table->tableGateway->getSql ();
	}
	
	/**
	 *
	 * @param unknown $params        	
	 * @return unknown
	 */
	public function findOne() {
		$params = $this->getParams ();
		if (! isset ( $params ['where'] ))
			$params ['where'] = array ();
		
		$rowset = $this->_table->tableGateway->select ( $params ['where'] );
		$this->count = $rowset->count ();
		try {
			if ($rowset->count ()) {
				$row = $rowset->current ();
			} else {
				$row = false;
			}
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $row;
	}
	
	/**
	 *
	 * @param unknown $params        	
	 * @return boolean|unknown
	 */
	public function findAll() {
		$params = $this->getParams ();
		$select = $this->getSql ()->select ();
		
		try {
			if (isset ( $params ['sort'] ))
				$select->order ( $params ['sort'] );
			
			if (! isset ( $params ['where'] ))
				$params ['where'] = array ();
			
			foreach ( $params ['where'] as $field => $value ) {
				$select->where->equalTo ( $field, $value );
			}
			
			$record = $this->_table->tableGateway->selectWith ( $select );
			$this->count = $record->count ();
			
			if ($record->count () < 1)
				$record = false;
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $record;
	}
	
	/**
	 *
	 * @param unknown $params        	
	 */
	public function findCount() {
		$params = $this->getParams ();
		
		$select = $this->getSql ()->select ();
		
		try {
			$select->columns ( array (
				'total' => new \Zend\Db\Sql\Expression ( 'count(*)' ) 
			) );
			
			if (! isset ( $params ['where'] ))
				$params ['where'] = array ();
			
			foreach ( $params ['where'] as $field => $value ) {
				$select->where->equalTo ( $field, $value );
			}
			
			$record = $this->_table->tableGateway->selectWith ( $select );
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $record->current ()->total;
	}
	
	/**
	 *
	 * @param unknown $id        	
	 * @return boolean|unknown
	 */
	public function find($id) {
		$id = ( int ) $id;
		$rowset = $this->_table->tableGateway->select ( array (
			'id' => $id 
		) );
		
		try {
			if ($rowset)
				$row = $rowset->current ();
			else
				$row = false;
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $row;
	}
	
	/**
	 *
	 * @param unknown $select        	
	 * @return \Zend\Paginator\Paginator
	 */
	public function getPaginator($select) {
		$Paginator = new \Zend\Paginator\Paginator ( new \Zend\Paginator\Adapter\Iterator ( $select ) );
		$Paginator->setCurrentPageNumber ( $this->_current_page );
		$Paginator->setItemCountPerPage ( $this->_items_per_page );
		return $Paginator;
	}
}
