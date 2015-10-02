<?php

namespace Model\Entity;

use Zend\Db\TableGateway\TableGateway;

/**
 * Every table must extend this, it provides gateway and other relevant db tasks
 * for common CRUD or search, count, fetch task, extend here
 * This should NOT contain table specific business logics or App specific business logics
 *
 * @author sandeepnarwal
 *        
 */
abstract class EntityTable implements \Zend\ServiceManager\ServiceLocatorAwareInterface {
	
	/**
	 *
	 * @var unknown
	 */
	protected $serviceLocator;
	
	/**
	 *
	 * @var unknown
	 */
	public $err;
	
	/**
	 *
	 * @var TableGateway
	 */
	public $tableGateway;
	
	/**
	 *
	 * @var unknown
	 */
	protected $errorMessage = null;
	
	/**
	 *
	 * @param TableGateway $tableGateway        	
	 */
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	/**
	 *
	 * @param unknown $func        	
	 * @param unknown $args        	
	 * @return unknown
	 */
	public function __call($func, $args) {
		if ($func == "prepareSave") {
			return $args [0];
		}
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function getFinder() {
		$class = get_class ( $this );
		
		// to remove "Table" in end of class name
		$class = substr ( $class, 0, strlen ( $class ) - 5 );
		$class = $class . "Finder";
		return new $class ( $this );
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function hasError() {
		return $this->err;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	/**
	 *
	 * @param unknown $msg        	
	 */
	public function setErrorMessage($msg) {
		$this->err = true;
		$this->errorMessage .= $msg;
	}
	
	/**
	 */
	public function clearErrorMessage() {
		$this->errorMessage = null;
	}
	
	/**
	 *
	 * @param unknown $code        	
	 */
	public function setErrorCode($code) {
		$this->errorCode = $code;
	}
	
	/**
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}
	
	/**
	 */
	public function clearErrorCode() {
		$this->errorCode = null;
	}
	
	/**
	 *
	 * @return TableGateway
	 */
	public function getTableGateway() {
		return $this->tableGateway;
	}
	
	/**
	 *
	 * @throws \Exception
	 * @return number
	 */
	public function save($entity, $where = null) {
		
		// if $entity do not exists, return false
		if ($entity == false) {
			return false;
		}
		
		$table = substr ( $this->getTableGateway ()->table, strlen ( \Model\Entity\Schema::$prefix ) );
		$columns = \Model\Entity\Schema::$schema [$table] ['columns'];
		foreach ( $columns as $key => $value ) {
			if (property_exists ( $entity, $value [0] ))
				$data [$value [0]] = $entity->{$value [0]};
		}
		
		// if id is set then also we need to update
		// create $where based on id in such a case
		try {
			
			if (! is_array ( $where )) {
					
				if ($data ['id'] > 0) {
					$where = array (
						"id" => $data ['id'] 
					);
					
					$this->tableGateway->update ( $data, $where );
					return $data ['id'];
				} else {
					// insert and return last ID
					$this->tableGateway->insert ( $data );
					return $this->tableGateway->lastInsertValue;
				}
			} else {
				$this->tableGateway->update ( $data, $where );
				return $data ['id'];
			}
		} catch ( \Exception $e ) {
			
			return \Model\Custom\Error::trigger ( $e, $params );
		}
	}
	
	/**
	 * delete by id of the table or array condition
	 *
	 * @param int/array $id        	
	 */
	public function delete($id) {
		try {
			if (is_array ( $id )) {
				$effectedRows = $this->tableGateway->delete ( $id );
			} else {
				$effectedRows = $this->tableGateway->delete ( array (
					'id' => ( int ) $id 
				) );
			}
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $effectedRows;
	}
}
