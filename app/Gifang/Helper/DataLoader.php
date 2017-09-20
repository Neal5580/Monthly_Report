<?php
namespace Gifang\Helper;

use Symfony\Component\Yaml\Parser;
use Gifang\Entity\Property;
use Gifang\Entity\Company;

/**
 * 
 * @author Yijie SHEN
 *
 */
class DataLoader {
	
	const ENV = "env/database.yml";
	
	protected $database;
	
	protected $sqls;
	
	public function __construct() {
		
		$reader = new Parser();
		
		$db_connection = $reader->parse(file_get_contents(self::ENV));
		
		$config = $db_connection['database'];
		
		$config['option'] = [\PDO::ATTR_CASE => \PDO::CASE_NATURAL];
		
		$this->database = new \medoo($config);
		
		$sql_reader = new SQLReader();
		
		$result = $sql_reader->getSQLs();
		
		$this->sqls = $result["sql"];
		
	}
	
	/**
	 * load properties by company
	 * 
	 * @param $company_id
	 */
	public function getPropertiesByCompany($company_id) {
		
		$query = $this->sqls["select_posts_by_company"];	
		
		$pdo = $this->database->pdo;
		
		$stmt = $pdo->prepare($query);
		
		$stmt->bindParam(":company_id", $company_id, \PDO::PARAM_STR);
		
		$stmt->execute();
		
		$data = $stmt->fetchAll(); 
		
		$result = array();
		
		foreach ($data as $row) {
			
			$property = new Property($this);			
			
			$property->setPid($row["ID"]);

			$property->setName($row["post_name"]);
			
			$property->setStatus($row["post_status"]);
			
			$meta = $this->getPropertyMeta($row["ID"]);
						
			$property->setState($meta["state"]);
			
			$address = "";
			
			if(key_exists("street_unit", $meta)){
				
				$address .= $meta["street_unit"].'/';
				
			}
			
			error_reporting(E_ERROR);
			
			$address .= $meta["street_number"].' '.$meta["street_name"].' '.$meta["street_type"].' '.strtoupper($meta['location']).', '.strtoupper($meta['state']).', '.strtoupper($meta['country']).' '.$meta['zip_code'];
			
			$property->setAddress($address);
			
			$result[] = $property;
			
		}
		
		return $result;
		
	}
	
	/**
	 * get property meta
	 * 
	 * @param unknown $post_id
	 */
	public function getPropertyMeta($post_id) {
		
		$query = $this->sqls["select_post_meta"];
		
		$pdo = $this->database->pdo;
		
		$stmt = $pdo->prepare($query);
		
		$stmt->bindParam(":post_id", $post_id, \PDO::PARAM_INT);
		
		$stmt->execute();
		
		$data = $stmt->fetchAll();
		
		$result = array();
		
		foreach ($data as $row) {
			
			$result[$row["meta_key"]] = $row["meta_value"];
			
		}
		
		return $result;
		
	}
	
	/**
	 * 
	 * 
	 * @return multitype:\Gifang\Entity\Company
	 */
	public function getCompanies() {
		
		$query = $this->sqls["select_companys"];
		
		$pdo = $this->database->pdo;
		
		$stmt = $pdo->prepare($query);
		
		$stmt->execute();
		
		$data = $stmt->fetchAll();
		
		$result = array();
		
		foreach ($data as $row) {
			
			$company = new Company($this);
			
			$company->setEmail($row["user_email"]);
			
			$company->setId($row["ID"]);
			
			$company->setName($row["display_name"]);			
			
			$result[$company->getId()] = $company;
			
		}
		
		return $result;
		
	}
	
	/**
	 * 
	 * get post visit count
	 * 
	 * @param unknown $post_id
	 * @param string $start_date
	 * @param string $end_date
	 * @return Ambigous <number, unknown>
	 */
	public function getPostVisitCount($post_id, $start_date = null, $end_date = null) {
		
		$end_date = $end_date ? new \DateTime($end_date) : new \DateTime();
		
		$temp_date = clone $end_date;
		
		$start_date = $start_date ? new \DateTime($start_date) : $temp_date->sub(new \DateInterval("P30D"));		

		$sql_sd_input = (string) $start_date->format("Y-m-d");
		
		$sql_ed_input = (string) $end_date->format("Y-m-d");
		
		$query = $this->sqls["select_post_visits"];
			
		$stmt = $this->database->pdo->prepare($query);
				
		$stmt->bindParam(":post_id", $post_id, \PDO::PARAM_STR);		
		
		$stmt->bindParam(":start_date", $sql_sd_input, \PDO::PARAM_STR);
		
		$stmt->bindParam(":end_date", $sql_ed_input, \PDO::PARAM_STR);
		
		$stmt->execute();
		
		$data = $stmt->fetchAll();
		
		$result = 0;
		
		foreach ($data as $row) {
			
			$result += $row["count"];
			
		}
				
		return $result;		
		
	}
	
}