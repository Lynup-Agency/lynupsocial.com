<?php

// Define some constants
define( "RECIPIENT_NAME", "Lynup Creative" ); //UPDATE THIS TO YOUR NAME
define( "RECIPIENT_EMAIL", "info@lynup.com" ); //UPDATE THIS TO YOUR EMAIL ID
define( "EMAIL_SUBJECT", "Lynup Website Visitor Message" ); //UPDATE THIS TO YOUR SUBJECT

// Read the form values
$success = false;
$senderFirstName = isset( $_POST['firstname'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['firstname'] ) : "";
$senderLastName = isset( $_POST['lastname'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['lastname'] ) : "";
$senderCompany = isset( $_POST['company'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['company'] ) : "";
$senderEmail = isset( $_POST['email'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['email'] ) : "";
$senderPhone = isset( $_POST['phone'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone'] ) : "";
$original_message = isset( $_POST['message'] ) ? preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'] ) : "";
$message = 'First Name: '.$senderFirstName.'<br/>Last Name: '.$senderLastName.' <br/>Company: ' .$senderCompany. '<br/>Phone: '.$senderPhone.'<br/>Email: '.$senderEmail.'<br/>Message: '.$original_message;

// If all values exist, send the email
if ( $senderFirstName && $senderLastName && $senderEmail && $message ) {
  $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
  $headers = "From: " . $senderName . " <" . $senderEmail . ">\n";
  $headers .= "MIME-Version: 1.0\n"; 
  $headers .= "Content-Type: text/HTML; charset=ISO-8859-1\n";
  $success = mail( $recipient, EMAIL_SUBJECT, $message, $headers );
}

if ( $success )
{
?>
	<script>
		window.location='thanks.html';
	</script>
<?php
}
else
{
	echo "<h1>There was a problem sending your message. Please try again.</h1>";
}
?>


