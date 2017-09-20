<?php
namespace Gifang\Model;

use Gifang\Entity\Company;
use Gifang\Variable;

/**
 * Report class
 * 
 * @author Yijie SHEN
 *
 */
class MonthlyReport {
	
	protected $company;
	
	protected $excel;
	
	protected $writer;
	
	public function __construct(Company $company) {
		
		$this->company = $company;
		
		$this->excel = new \PHPExcel();
		
		$this->writer = new \PHPExcel_Writer_Excel2007();
		
	}
	
	public function saveReport() {
		
		$grouped_report = $this->company->groupPropertiesByState();
		
		if (sizeof($grouped_report) == 0) {
			
			return $this;
			
		}
		
		$count = 0;
		
		foreach ($grouped_report as $key => $properties) {
			
			if (in_array(strtoupper($key), array('VIC','NSW','TAS','WA','QLD','ACT','JBT','SA','NT'))) {
 
 				if ($count != 0) {$this->excel->createSheet();}//if not the first sheet create new sheet		
				
				if ($count == 0) {$this->excel->setActiveSheetIndex($count);}//set active sheet index	
				
				$sheet = $this->excel->getSheet($count);			
				
				foreach (range("A", "Z") as $col) {
					
					$sheet->getColumnDimension($col)->setAutoSize(true);
					
				}			
				
				$sheet->setCellValue("A1","Gifang.com (Mandarin)");
				
				$sheet->setCellValue("B1", $this->company->getName());
				
				$sheet->setCellValue("B2", "Period: ".$this->getPeriod());			
				
				$sheet->setCellValue("A4", "Property address");
				
				$sheet->setCellValue("B4", "Company name");
				
				$sheet->setCellValue("C4", "Status");
	
				$sheet->setCellValue("D4", "Total visited this month");
				
				$this->setStyle($sheet, "B2", "D4", Variable::$align_center);
				
				$this->setStyle($sheet, "A4", "D4", Variable::$header_style);
				
				$total = 0;
				
				for ($i = 0 ; $i < sizeof($properties); $i++) {
					
					$row = 5+$i;
					
					$lmv = $properties[$i]->getLastMonthVisit();
					
					$sheet->setCellValue("A$row", $properties[$i]->getAddress());
					
					$sheet->setCellValue("B$row", $this->company->getName());
					
					$sheet->setCellValue("C$row", $properties[$i]->getStatus());
					
					$sheet->setCellValue("D$row", $lmv == 0 ? "unpublished" : $lmv);
					
					$this->setStyle($sheet, "B$row", "D$row", Variable::$align_center);
					
					if ($i%2 == 0) {
						
						$this->setStyle($sheet, "A$row", "D$row", Variable::$odd_row);
						
					}
					
					$total += $lmv;
					
				}
				
				$sheet->setCellValue("C2", "total visited this month: ".$lmv);
				
				$this->excel->getSheet($count)->setTitle($key);
				
				$count++;
				
			}
			
		}
			
		$this->writer->setPHPExcel($this->excel);
						
		$this->writer->save("var/report/".$this->company->getName().".xlsx");

		return $this;
		
	}
	
	protected function setStyle(\PHPExcel_Worksheet $sheet, $from, $end, $style) {

		$sheet->getStyle("$from:$end")->applyFromArray($style);
		
	}	
	
	protected function getPeriod() {
		
		$now = new \DateTime();
		
		return $now->format("Y-m");
		
	}
	
}