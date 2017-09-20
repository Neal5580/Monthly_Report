<?php
namespace Gifang\Model;

use Gifang\Entity\Company;
use Gifang\Variable;

/**
 * Email class
 * 
 * @author Yijie SHEN
 *
 */
class Send_Email {
	
	var $name;

	var $email;

	var $id;

	public function __construct(Company $company) {	

		$this->id = $company->getId();

		$this->email = $company->getEmail();

		$this->name = $company->getName();

	}

	public function sendReport() {

		$mail = new \PHPMailer();
		
		$mail->isSMTP();                            // Set mailer to use SMTP
		
		$mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
		
		$mail->SMTPAuth = true;                     // Enable SMTP authentication
		
		$mail->Username = 'it@gifang.com';          // SMTP username
		
		$mail->Password = 'gifangit@world'; 		// SMTP password
		
		$mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
		
		$mail->Port = 587;                          // TCP port to connect to

		$mail->setFrom('it@gifang.com', 'Gifang.com');
		
		$mail->addReplyTo('it@gifang.com', 'Gifang.com');
		
		$mail->addAddress('neal.s@au.gifang.com');   // Add a recipient
		
		$mail->addCC('giselley@au.gifang.com');
		
		$mail->isHTML(true);  						 // Set email format to HTML

		$bodyContent = '<p>Hi <b>' . $this->name . '</b>,</p>';
		
		$bodyContent .= '<p>This is <b>Giselle Yu</b> of <a href="http://www.gifang.com">GiFang.com</a>, your platform to reach Chinese buyers.</p>';

		$bodyContent .= '<p>Please see attachment for your listing performance statistics from <a href="http://www.gifang.com">GiFang.com</a></p>';

		$bodyContent .= '<p>It contains all properties listed by your employees and their click-throughs.</p>';

		$bodyContent .= '<p>Your office profile can be viewed here:</p>';

		$bodyContent .= '<p>http://www.gifang.com/agent/real_estate_agent/' . $this->id .'</p>';

		$bodyContent .= '<p>Please feel free to contact us if you have any questions</p>';

		$bodyContent .= '<p>Once again thank your for choosing <a href="http://www.gifang.com">GiFang.com</a></p>';

		$mail->Subject = 'Hi, ' . (string)$this->name . 'Please see the monthly stats of your properties on GiFang.com';
		
		$mail->Body    = $bodyContent;
		
		$path = "var/report/". (string)$this->name . ".xlsx";  //attachment path
		
		$mail->addAttachment((string)$path);  		  //add monthly report as attacment

		if(!$mail->send()) {
    	
			echo 'Message could not be sent.';
    
    	echo 'Mailer Error: ' . $mail->ErrorInfo;
	
		} else {
   	
   			echo 'Message has been sent';
	
		}
	
	}
	
}