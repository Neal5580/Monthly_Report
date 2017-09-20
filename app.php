<?php
use Gifang\Helper\SQLReader;
use Gifang\Helper\DataLoader;
use Gifang\Model\MonthlyReport;
use Gifang\Model\Send_Email;

require_once 'bootstrap.php';

$dataloader = new DataLoader();

$companies = $dataloader->getCompanies();

$total_size = sizeof($companies);

$count = 0;

foreach ($companies as $c_key => $company) {	
		
	$report = new MonthlyReport($company);
		
	$report->saveReport();
	
	$count++;

   	$send_email = new Send_Email($company);

	$send_email->sendReport();
	
	$percent = round($count / $total_size * 100);	
	
	echo "Processing: $percent%\r";	
	
}

