<?php

function getEmailBodyTemplate($subject = '', $header = '', $headline = '', $body = '', $footer = ''){
	$emailBody = '';
	
	$emailBody .= 
	"<!doctype html>
	<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
		<head>
			<meta charset='UTF-8'>
			<meta http-equiv='X-UA-Compatible' content='IE=edge'>
			<meta name='viewport' content='width=device-width, initial-scale=1'>
			<title>{$subject}</title>
			
			<style type='text/css'>
			p{
				margin:10px 0;
				padding:0;
			}
			table{
				border-collapse:collapse;
			}
			h1,h2,h3,h4,h5,h6{
				display:block;
				margin:0;
				padding:0;
			}
			img,a img{
				border:0;
				height:auto;
				outline:none;
				text-decoration:none;
			}
			body,#bodyTable,#bodyCell{
				height:100%;
				margin:0;
				padding:0;
				width:100%;
			}
			.mcnPreviewText{
				display:none !important;
			}
			#outlook a{
				padding:0;
			}
			img{
				-ms-interpolation-mode:bicubic;
			}
			table{
				mso-table-lspace:0pt;
				mso-table-rspace:0pt;
			}
			.ReadMsgBody{
				width:100%;
			}
			.ExternalClass{
				width:100%;
			}
			p,a,li,td,blockquote{
				mso-line-height-rule:exactly;
			}
			a[href^=tel],a[href^=sms]{
				color:inherit;
				cursor:default;
				text-decoration:none;
			}
			p,a,li,td,body,table,blockquote{
				-ms-text-size-adjust:100%;
				-webkit-text-size-adjust:100%;
			}
			.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
				line-height:100%;
			}
			a[x-apple-data-detectors]{
				color:inherit !important;
				text-decoration:none !important;
				font-size:inherit !important;
				font-family:inherit !important;
				font-weight:inherit !important;
				line-height:inherit !important;
			}
			.templateContainer{
				max-width:600px !important;
			}
			a.mcnButton{
				display:block;
			}
			.mcnImage,.mcnRetinaImage{
				vertical-align:bottom;
			}
			.mcnTextContent{
				word-break:break-word;
			}
			.mcnTextContent img{
				height:auto !important;
			}
			.mcnDividerBlock{
				table-layout:fixed !important;
			}
			body,#bodyTable{
				background-color:#FAFAFA;
			}
			#bodyCell{
				border-top:0;
			}
			h1{
				color:#202020;
				font-family:Helvetica;
				font-size:26px;
				font-style:normal;
				font-weight:bold;
				line-height:125%;
				letter-spacing:normal;
				text-align:left;
			}
			h2{
				color:#202020;
				font-family:Helvetica;
				font-size:22px;
				font-style:normal;
				font-weight:bold;
				line-height:125%;
				letter-spacing:normal;
				text-align:left;
			}
			h3{
				color:#202020;
				font-family:Helvetica;
				font-size:20px;
				font-style:normal;
				font-weight:bold;
				line-height:125%;
				letter-spacing:normal;
				text-align:left;
			}
			h4{
				color:#202020;
				font-family:Helvetica;
				font-size:18px;
				font-style:normal;
				font-weight:bold;
				line-height:125%;
				letter-spacing:normal;
				text-align:left;
			}
			#templatePreheader{
				background-color:#FAFAFA;
				background-image:none;
				background-repeat:no-repeat;
				background-position:center;
				background-size:cover;
				border-top:0;
				border-bottom:0;
				padding-top:9px;
				padding-bottom:9px;
			}
			#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
				color:#656565;
				font-family:Helvetica;
				font-size:12px;
				line-height:150%;
				text-align:left;
			}
			#templatePreheader .mcnTextContent a,#templatePreheader .mcnTextContent p a{
				color:#656565;
				font-weight:normal;
				text-decoration:underline;
			}
			#templateHeader{
				background-color:#FFFFFF;
				background-image:none;
				background-repeat:no-repeat;
				background-position:center;
				background-size:cover;
				border-top:0;
				border-bottom:0;
				padding-top:9px;
				padding-bottom:0;
			}
			#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
				color:#202020;
				font-family:Helvetica;
				font-size:16px;
				line-height:150%;
				text-align:left;
			}
			#templateHeader .mcnTextContent a,#templateHeader .mcnTextContent p a{
				color:#2BAADF;
				font-weight:normal;
				text-decoration:underline;
			}
			#templateBody{
				background-color:#FFFFFF;
				background-image:none;
				background-repeat:no-repeat;
				background-position:center;
				background-size:cover;
				border-top:0;
				border-bottom:0;
				padding-top:9px;
				padding-bottom:9px;
			}
			#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
				color:#202020;
				font-family:Helvetica;
				font-size:16px;
				line-height:150%;
				text-align:left;
			}
			#templateBody .mcnTextContent a,#templateBody .mcnTextContent p a{
				color:#2BAADF;
				font-weight:normal;
				text-decoration:underline;
			}
			#templateColumns{
				background-color:#FFFFFF;
				background-image:none;
				background-repeat:no-repeat;
				background-position:center;
				background-size:cover;
				border-top:0;
				border-bottom:2px solid #EAEAEA;
				padding-top:0;
				padding-bottom:9px;
			}
			#templateColumns .columnContainer .mcnTextContent,#templateColumns .columnContainer .mcnTextContent p{
				color:#202020;
				font-family:Helvetica;
				font-size:16px;
				line-height:150%;
				text-align:left;
			}
			#templateColumns .columnContainer .mcnTextContent a,#templateColumns .columnContainer .mcnTextContent p a{
				color:#2BAADF;
				font-weight:normal;
				text-decoration:underline;
			}
			#templateFooter{
				background-color:#FAFAFA;
				background-image:none;
				background-repeat:no-repeat;
				background-position:center;
				background-size:cover;
				border-top:0;
				border-bottom:0;
				padding-top:9px;
				padding-bottom:9px;
			}
			#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
				color:#656565;
				font-family:Helvetica;
				font-size:12px;
				line-height:150%;
				text-align:center;
			}
			#templateFooter .mcnTextContent a,#templateFooter .mcnTextContent p a{
				color:#656565;
				font-weight:normal;
				text-decoration:underline;
			}
		@media only screen and (min-width:768px){
			.templateContainer{
				width:600px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			body,table,td,p,a,li,blockquote{
				-webkit-text-size-adjust:none !important;
			}
	
	}	@media only screen and (max-width: 480px){
			body{
				width:100% !important;
				min-width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#bodyCell{
				padding-top:10px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.columnWrapper{
				max-width:100% !important;
				width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnRetinaImage{
				max-width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImage{
				width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
				max-width:100% !important;
				width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnBoxedTextContentContainer{
				min-width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImageGroupContent{
				padding:9px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
				padding-top:9px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
				padding-top:18px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImageCardBottomImageContent{
				padding-bottom:9px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImageGroupBlockInner{
				padding-top:0 !important;
				padding-bottom:0 !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImageGroupBlockOuter{
				padding-top:9px !important;
				padding-bottom:9px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnTextContent,.mcnBoxedTextContentColumn{
				padding-right:18px !important;
				padding-left:18px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
				padding-right:18px !important;
				padding-bottom:0 !important;
				padding-left:18px !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcpreview-image-uploader{
				display:none !important;
				width:100% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			h1{
				font-size:22px !important;
				line-height:125% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			h2{
				font-size:20px !important;
				line-height:125% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			h3{
				font-size:18px !important;
				line-height:125% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			h4{
				font-size:16px !important;
				line-height:150% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
				font-size:14px !important;
				line-height:150% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#templatePreheader{
				display:block !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
				font-size:14px !important;
				line-height:150% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
				font-size:16px !important;
				line-height:150% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
				font-size:16px !important;
				line-height:150% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#templateColumns .columnContainer .mcnTextContent,#templateColumns .columnContainer .mcnTextContent p{
				font-size:16px !important;
				line-height:150% !important;
			}
	
	}	@media only screen and (max-width: 480px){
			#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
				font-size:14px !important;
				line-height:150% !important;
			}
	
	}
	</style>
	
	</head>";
	
	
	$emailBody .= "
	<body style='height: 100%;margin: 0;padding: 0;width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FAFAFA;'>
	<center>
	<table align='center' border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' id='bodyTable' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;margin: 0;padding: 0;width: 100%;background-color: #FAFAFA;'>
	<tr>
	<td align='center' valign='top' id='bodyCell' style='mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;margin: 0;padding: 0;width: 100%;border-top: 0;'>
	";
	
	
	$emailBody .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>";
	
		$emailBody .= "<tr>
						<td align='center' valign='top' id='templatePreheader' style='background:#FAFAFA none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FAFAFA;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 9px;padding-bottom: 9px;'>
							<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;'>
								<tr>
									<td valign='top' class='preheaderContainer' style='mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
										<table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnTextBlock' style='min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
											<tbody class='mcnTextBlockOuter'>
											<tr>
												<td valign='top' class='mcnTextBlockInner' style='padding-top: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
													<table align='left' border='0' cellpadding='0' cellspacing='0' style='max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;' width='100%' class='mcnTextContentContainer'>
														<tbody>
														<tr>
															<td valign='top' class='mcnTextContent' style='padding: 0px 18px 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #656565;font-family: Helvetica;font-size: 12px;line-height: 150%;'>
																{$header}
															</td>
														</tr>
														</tbody>
													</table>
												</td>
											</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
					
		$emailBody .= "<tr>
						<td align='center' valign='top' id='templateHeader' style='background:#FFFFFF none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 9px;padding-bottom: 0;'>
							<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;'>
								<tr>
									<td valign='top' class='headerContainer' style='mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
									
										<table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnCaptionBlock' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
										<tbody class='mcnCaptionBlockOuter'>
										<tr>
											<td class='mcnCaptionBlockInner' valign='top' style='padding: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
												<table align='left' border='0' cellpadding='0' cellspacing='0' class='mcnCaptionBottomContent' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
													<tbody>
													<tr>
														<td class='mcnTextContent' valign='top' style='padding: 0 9px 0 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #202020;font-family: Helvetica;font-size: 16px;line-height: 150%; text-align: left;' width='564'>
															{$headline}
														</td>
													</tr>
													</tbody>
												</table>
											</td>
										</tr>
										</tbody>
										</table>
										
										
										
										
									</td>
								</tr>
							</table>
						</td>
					</tr>";
					
				
		$emailBody .= "<tr>
						<td align='center' valign='top' id='templateBody' style='background:#FFFFFF none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 9px;padding-bottom: 9px;'>
							<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;'>
								<tr>
									<td valign='top' class='bodyContainer' style='mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
									<table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnDividerBlock' style='min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;table-layout: fixed !important;'>
										<tbody class='mcnDividerBlockOuter'>
										<tr>
											<td class='mcnDividerBlockInner' style='min-width: 100%;padding: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
												<table class='mcnDividerContent' border='0' cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%;border-top: 2px solid #EAEAEA;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%; line-height: 150%'>
													<tbody>
													<tr>
														<td>
															<div style='mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%; color: #202020;font-family: Helvetica;font-size: 12px;'>
																{$body}
															</div>
															
															
														</td>
													</tr>
													</tbody>
												</table>
											</td>
										</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
					
		
		$emailBody .= "<tr>
				<td align='center' valign='top' id='templateFooter' style='background:#FAFAFA none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FAFAFA;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 9px;padding-bottom: 9px;'>
					
				   <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer' style='border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;'>
								<tr>
									<td valign='top' class='preheaderContainer' style='mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
										<table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnTextBlock' style='min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
											<tbody class='mcnTextBlockOuter'>
											<tr>
												<td valign='top' class='mcnTextBlockInner' style='padding-top: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>
													<table align='left' border='0' cellpadding='0' cellspacing='0' style='max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;' width='100%' class='mcnTextContentContainer'>
														<tbody>
														<tr>
															<td valign='top' class='mcnTextContent' style='padding: 0px 18px 9px;text-align: left; mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #656565;font-family: Helvetica;font-size: 12px;line-height: 150%;'>
																{$footer}
															</td>
														</tr>
														</tbody>
													</table>
												</td>
											</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</table>
					
				</td>
			</tr>";
			
	$emailBody .= "</table>";
		
	$emailBody .= "</td>
	</tr>
	</table>
	</center>
	</body>
	</html>";
	
	return $emailBody;	
}

function addOutgoingEmails($inputArray = array(), $receipents = array(), $sendNow = 0){
	
	global $APP_CONFIG;
	
	
	if (array_size($receipents) <= 0){
		if (is_string($receipents)){
			$temp = $receipents;
			$receipents = array();
			$receipents[0]['Email'] = trim($temp);
			
			if ($receipents[0]['Email'] == ''){
				return false;	
			}
			
		} else {
			return false;	
		}
	}
	
	
	if ($inputArray['Subject'] == '') return false;
	
	if ($inputArray['Body'] == '') return false;
	
	$commonArray = array();
	
	$commonArray['Table'] 				= $APP_CONFIG['TABLES']['EMAIL'];
	$commonArray['Created_By_ID'] 		= $_SESSION['User_Info']['ID'];
	$commonArray['Created_By_Name'] 	= $_SESSION['User_Info']['Name'];
	$commonArray['Created_Date'] 		= date('Y-m-d');
	$commonArray['Created_DateTime'] 	= date('Y-m-d H:i:s');
	
	$commonArray['From_User_ID'] 		= $inputArray['From_User_ID'];
	if ($commonArray['From_User_ID'] == ''){
		$commonArray['From_User_ID'] 	= $_SESSION['User_Info']['ID'];
	}
	
	$commonArray['From_User_Name'] 		= $inputArray['From_User_Name'];
	if ($commonArray['From_User_Name'] == ''){
		$commonArray['From_User_Name'] 	= $_SESSION['User_Info']['Name'];
	}
	
	$commonArray['From_User_Email'] 	= $inputArray['From_User_Email'];
	if ($commonArray['From_User_Email'] == ''){
		$commonArray['From_User_Email'] = $_SESSION['User_Info']['Email'];
	}
	
	
	$commonArray['Record_Table'] 		= $inputArray['Record_Table'];
	$commonArray['Record_ID'] 			= $inputArray['Record_ID'];
	$commonArray['Subject'] 			= $inputArray['Subject'];
	$commonArray['Body']				= $inputArray['Body'];
	
	
	$commonArray['Schedule_Date'] 		= $inputArray['Schedule_Date'];
	if ($commonArray['Schedule_Date'] == ''){
		$commonArray['Schedule_Date'] = date('Y-m-d');
	}
	
	$commonArray['Schedule_DateTime'] 	= $inputArray['Schedule_DateTime'];
	if ($commonArray['Schedule_DateTime'] == ''){
		$commonArray['Schedule_DateTime'] = date('Y-m-d H:i:s');
	}
	
	
	$dataArray = array();
	$currentIndex = -1;
	

	foreach($receipents as $tempKey => $currentReceipent){
		
		if ($currentReceipent['Name'] == ''){
			$currentReceipent['Name'] = $currentReceipent['Email'];	
		}
		
		
		$currentIndex++;
		$dataArray[$currentIndex] = $commonArray;
		$dataArray[$currentIndex]['To_User_ID'] 		= $currentReceipent['ID'];
		$dataArray[$currentIndex]['To_User_Name'] 		= $currentReceipent['Name'];
		$dataArray[$currentIndex]['To_User_Email'] 		= $currentReceipent['Email'];
		
		
		if ($sendNow){
			$dataArray[$currentIndex]['Sent_Date'] 		= date('Y-m-d');
			$dataArray[$currentIndex]['Sent_DateTime'] 	= date('Y-m-d H:i:s');
			$dataArray[$currentIndex]['Is_Sent'] 		= 1;
			
			sendEmailNow($currentReceipent['Email'], $currentReceipent['Name'], 
							$commonArray['From_User_Email'], $commonArray['From_User_Name'], 
							$commonArray['Subject'], $commonArray['Body']);
			
		}
		
	}
	
	
	
	
	if (array_size($dataArray) > 0){
		$SQL = getInsertMultipleSQLQuery($APP_CONFIG['TABLES']['EMAIL'], $dataArray);
		executeSQL($SQL);
	}
	
	return true;
	
}

function sendEmailNow($toEmail = '', $toName = '', $fromEmail = '', $fromName = '', $title = '', $body = ''){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$sent = false;
		
	if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)){
		return false;
	}
	
	if (function_exists($BXAF_CONFIG['SENDEMAIL_VIA_FUNCTION']) && ($BXAF_CONFIG['SENDEMAIL_VIA_FUNCTION'] != '')){
		$result = $BXAF_CONFIG['SENDEMAIL_VIA_FUNCTION']($toEmail, $toName, $fromEmail, $fromName, $title, $body);
		$sent = true;
	} elseif ($BXAF_CONFIG['EMAIL_METHOD'] = 'url'){
		$result = sendEmailViaURL($toEmail, $toName, $fromEmail, $fromName, $title, $body);
		$sent = true;
	} elseif ($BXAF_CONFIG['EMAIL_METHOD'] = 'mail'){
		$result = mail($toEmail, $title, $body);
		$sent = true;	
	}

	
	return true;
	
}

function sendPendingEmails(){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$currentTime = date('Y-m-d H:i:s');
	
	$SQL = "SELECT * FROM `{$APP_CONFIG['TABLES']['EMAIL']}` WHERE (`Schedule_DateTime` < '{$currentTime}') AND (`Is_Canceled` = 0) AND (`Is_Sent` = 0)";
	
	
	$allEmails = getSQL_Data($SQL, 'GetAssoc', 0);
	
	if (array_size($allEmails) <= 0) return false;
	
	
	foreach($allEmails as $emailID => $currentEmail){
		
		$sent = false;
		
		if (!filter_var($currentEmail['To_User_Email'], FILTER_VALIDATE_EMAIL)){
			continue;
		}
		
		if (function_exists($BXAF_CONFIG['SENDEMAIL_VIA_FUNCTION']) && ($BXAF_CONFIG['SENDEMAIL_VIA_FUNCTION'] != '')){
			$BXAF_CONFIG['SENDEMAIL_VIA_FUNCTION']($currentEmail['To_User_Email'], $currentEmail['To_User_Name'], $currentEmail['From_User_Email'], $currentEmail['From_User_Name'], $currentEmail['Subject'], $currentEmail['Body']);
			$sent = true;
		} elseif ($BXAF_CONFIG['EMAIL_METHOD'] = 'url'){
			$result = sendEmailViaURL($currentEmail['To_User_Email'], $currentEmail['To_User_Name'], $currentEmail['From_User_Email'], $currentEmail['From_User_Name'], $currentEmail['Subject'], $currentEmail['Body']);
			$sent = true;
		} elseif ($BXAF_CONFIG['EMAIL_METHOD'] = 'mail'){
			$result = mail($currentEmail['To_User_Email'], $currentEmail['Subject'], $currentEmail['Body']);
			$sent = true;	
		}

		
		if ($sent){
			
			$dataArray = array();
			$dataArray['Sent_Date'] 	= date('Y-m-d');
			$dataArray['Sent_DateTime'] = date('Y-m-d H:i:s');
			$dataArray['Is_Sent'] 		= 1;
			$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['EMAIL'], $dataArray, $emailID);
			executeSQL($SQL);
		}
		
		
	}
}

function sendEmailViaURL($toEmail = '', $toName = '', $fromEmail = '', $fromName = '', $title = '', $body = ''){
	
	global $BXAF_CONFIG;
	
	$email['To']['Name']	= trim($toName);
	$email['To']['Email']	= trim($toEmail);
	
	$email['From']['Name']	= trim($fromName);
	$email['From']['Email']	= trim($fromEmail);
	
	$email['Title']			= trim($title);
	
	$email['Body']			= trim($body);

	
	$emailToSend			= json_encode($email);

	$emailToSend_Encrypted	= encrypt_string($emailToSend, $BXAF_CONFIG['SENDEMAIL_VIA_URL_KEY']);
		
	$postdata = http_build_query(
		array(
			'email' => $emailToSend_Encrypted
		)
	);
	
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-Type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);
	
	$context  = stream_context_create($opts);
	
	$result = file_get_contents($BXAF_CONFIG['SENDEMAIL_VIA_URL_POST'], false, $context);

	return intval(file_get_contents($URL));
	
	
}


?>