<?php
namespace Gifang\Helper;

use Symfony\Component\Yaml\Parser;
/**
 * 
 * Read SQL from yaml file
 * 
 * @author Yijie SHEN
 *  
 */
class SQLReader  {
	
	CONST PATH = "env/sql.yml";
	
	protected $reader;
	
	protected $sqls;
	
	/**
	 * construct
	 */
	public function __construct() {
		
		$this->reader = new Parser();
		
	}
	
	/**
	 * Get SQLs
	 * 
	 * @return \Symfony\Component\Yaml\mixed
	 */
	public function getSQLs() {
		
		if (!$this->sqls) {
			
			$this->sqls = $this->reader->parse(file_get_contents(self::PATH));
			
		}
		
		return $this->sqls;
		
	}
		
}
