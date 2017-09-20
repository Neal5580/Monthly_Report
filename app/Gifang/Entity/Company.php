<?php
namespace Gifang\Entity;

use Gifang\Helper\DataLoader;
/**
 * 
 * Report Company
 * 
 * @author Yijie SHEN
 *
 */
class Company {
	
	protected $uid;
	
	protected $name;
	
	protected $email;
	
	protected $properties;
	
	protected $loader;
	
	public function __construct(DataLoader $loader) {
		
		$this->properties = array();
		
		$this->loader = $loader;
		
	}
	
	public function setId($id) {
		
		$this->uid = $id;
		
		return $this;
		
	}
	
	public function getId() {
		
		return $this->uid;
		
	}
	
	public function setName($name) {
		
		$this->name = $name;
		
		return $this;
		
	} 
	
	public function getName() {
		
		return $this->name;
		
	}
	
	public function setEmail($email) {
		
		$this->email = $email;
		
		return $this;
		
	}
	
	public function getEmail() {
		
		return $this->email;
		
	}	
	
	public function addProperty(Property $property) {
				
		$this->properties[] = $property;
		
		return $this;
		
	}
	
	public function getProperties() {
		
		if (!$this->properties) {
			
			$this->properties = $this->loader->getPropertiesByCompany($this->uid);
			
		}
		
		return $this->properties;
				
	}
	
	public function groupPropertiesByState() {
		
		$properties = $this->getProperties();
		//var_dump($this->getId());
		$result = array();
		
		foreach ($properties as $property) {
					
			$result[$property->getState()][] = $property;
			
		}		
		
		return $result;
		
	}
	
}