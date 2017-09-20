<?php
namespace Gifang\Entity;

use Gifang\Helper\DataLoader;

class Property {
	
	protected $name;
	
	protected $state;
	
	protected $pid;	
	
	protected $loader;
	
	protected $status;
	
	protected $address;
	
	protected $last_mont_visits;
	
	public function __construct(DataLoader $loader) {
		
		$this->loader = $loader;
		
	}
			
	public function getName() {
		
		return $this->name;
		
	}
	
	public function setName($name) {
		
		$this->name = $name;
		
		return $this;
		
	}
	
	public function getState() {
		
		return $this->state;
		
	}
	
	public function setState($state) {
		
		$this->state = $state;
		
		return $this;
	}
	
	public function getPid() {
		
		return $this->pid;
	}
	
	public function setPid($pid) {
		
		$this->pid = $pid;
		
		return $this;
	} 
	
	public function getLastMonthVisit() {
		
		if (!$this->last_mont_visits) {
			
			$this->last_mont_visits = $this->loader->getPostVisitCount($this->pid);
			
		}
		
		return $this->last_mont_visits;		
	}
	
	public function getStatus() {
		
		return $this->status;
		
	}
	
	public function setStatus($status) {
		
		$this->status = $status;
		
		return $this;
		
	}
	
	public function getAddress() {
		
		return $this->address;
		
	}
	
	public function setAddress($address) {
		
		$this->address = $address;
		
		return $this;
		
	}
}