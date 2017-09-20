<?php

namespace Gifang;

class Variable {
	
	public static $align_center = array (
			'alignment' => array (
					'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
			) 
	);
	
	public static $header_style = array(
			'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'name'  => 'Arial'
			),
			'alignment' => array (
					'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			),
			'fill' => array(
					'type' => \PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => '4AACC5')
			)
	);
	
	public static $odd_row = array(			
			'fill' => array(
					'type' => \PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'DCE6F1')
			)
	);
	
}