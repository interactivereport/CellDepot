<?php
include_once(dirname(__FILE__) . "/config.php");


Set_Time_Limit(300); // 5 min limit
Ignore_User_Abort(true); // this will force the script running in the background, even the browser is closed.



$email_param = array();

if(! array_key_exists('From', $_POST)) $email_param['From'] = $BXAF_CONFIG['BXAF_PAGE_EMAIL'];
else $email_param['From'] = $_POST['From'];

if(! array_key_exists('FromName', $_POST)) $email_param['FromName'] = $BXAF_CONFIG['BXAF_PAGE_APP_NAME'];
else $email_param['FromName'] = $_POST['FromName'];

$email_param['ReplyTo'] = isset($_POST['ReplyTo']) ? $_POST['ReplyTo'] : $email_param['From'];
$email_param['ReplyToName'] = isset($_POST['ReplyToName']) ? $_POST['ReplyToName'] : $email_param['FromName'];
$email_param['To'] = $_POST['To'];
$email_param['Subject'] = $_POST['Subject'];
$email_param['Body'] = $_POST['Body'];
$email_param['files'] = $_POST['files'];

bxaf_send_email($email_param);

?>