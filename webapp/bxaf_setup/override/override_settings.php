<?php
//Time Zone
date_default_timezone_set("America/Chicago");

//The name of the application. This will be displayed on the upper left corner.
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_APP_NAME'] 				= 'CellDepot';
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_TITLE'] 					= 'CellDepot';

$BXAF_CONFIG_CUSTOM['BXAF_PAGE_AUTHOR']					= 'Derrick Cheng (derrick@bioinforx.com)';

//Make sure that all pages require user to login
$BXAF_CONFIG_CUSTOM['BXAF_LOGIN_REQUIRED'] 				= true;

//Footer Text
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_FOOTER_CONTENT']			= '';


//The full path to the SQLlite database file
$BXAF_CONFIG_CUSTOM['BXAF_DB_DRIVER'] 					= 'sqlite';
$BXAF_CONFIG_CUSTOM['BXAF_DB_NAME']                     = '/var/www/html/celldepot/bxaf_setup/override/users.db';

//The MySQL settings
$BXAF_CONFIG_CUSTOM['APP_DB_DRIVER']               	 	= 'mysql';
$BXAF_CONFIG_CUSTOM['APP_DB_SERVER'] 					= 'localhost';
$BXAF_CONFIG_CUSTOM['APP_DB_USER'] 						= 'mysql_user_name';
$BXAF_CONFIG_CUSTOM['APP_DB_PASSWORD'] 					= 'mysql_password';
$BXAF_CONFIG_CUSTOM['APP_DB_NAME'] 				   		= 'db_celldepot';


//The Redis settings

$BXAF_CONFIG_CUSTOM['REDIS_ENABLE']						= true;

$BXAF_CONFIG_CUSTOM['REDIS_PREFIX']						= 'CellDepot';

$BXAF_CONFIG_CUSTOM['COOKIE_PREFIX']					= 'CellDepot';



//The default password
$BXAF_CONFIG_CUSTOM['BXAF_USER_DEFAULT_PASSWORD']		= '';

//Admin Users
$BXAF_CONFIG_CUSTOM['Admin_User_Email']					= array('admin@bioinforx.com');

//Power Users
$BXAF_CONFIG_CUSTOM['Power_User_Email']					= array();

//API Key
$BXAF_CONFIG_CUSTOM['API_Key']							= '';




// Page styles using CSS classes from Bootstrap 4
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_CSS_MENU'] 				= 'navbar-light bg-faded';
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_CSS_FOOTER']	    		= 'w-100 bg-faded text-center text-muted py-3';
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_CSS_FOOTER']	    		= 'bxaf_page_footer table-info text-center text-muted py-3';




$BXAF_APP_SUBDIR                                        = "celldepot/app/";
$BXAF_CUSTOMER_SUBDIR                                   = "celldepot/bxaf_setup/CellDepot/";
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_HEADER_CUSTOM_CSS']		= "/{$BXAF_APP_SUBDIR}css/page.css";
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_HEADER_CUSTOM_JS']		= "/{$BXAF_APP_SUBDIR}js/page.js";
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_APP_LOGO_URL'] 			= "/{$BXAF_CUSTOMER_SUBDIR}Logo_35h.png";
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_APP_URL_ICON']			= "/{$BXAF_CUSTOMER_SUBDIR}Logo_35h.png";
$BXAF_CONFIG_CUSTOM['BXAF_LOGIN_SUCCESS'] 				= "/{$BXAF_APP_SUBDIR}core/index.php";




//The full path to the Work directory
$BXAF_CONFIG_CUSTOM['WORK_DIR']							= '/var/www/html/celldepot_share/';


//The full path to the H5AD / CSC Files
$BXAF_CONFIG_CUSTOM['DATA_UPLOAD_DIR']					= '/data/celldepot/all_h5ad_files/';


//Send email now or later
$BXAF_CONFIG_CUSTOM['EMAIL_SEND_NOW']     				= 0;



$BXAF_CONFIG_CUSTOM['GUEST_ACCOUNT']					= 'guest@bioinforx.com';

$BXAF_CONFIG_CUSTOM['GUEST_ACCOUNT_AUTO']				= true;

$BXAF_CONFIG_CUSTOM['USER_SIGNUP_ENABLE']				= false;

//API Key
$BXAF_CONFIG_CUSTOM['API_Key']							= 'my_secret_api_key';

$currentID = 1;
$BXAF_CONFIG_CUSTOM['Server'][$currentID]['Title'] 						= 'My Storage Server';
$BXAF_CONFIG_CUSTOM['Server'][$currentID]['URL'] 						= 'https://celldepot-vip.example.com/d/{File_Name}/';
$BXAF_CONFIG_CUSTOM['Server'][$currentID]['File_Directory'] 			= '/data/celldepot/all_h5ad_files/';
$BXAF_CONFIG_CUSTOM['Server'][$currentID]['File_Directory_toCSCh5ad'] 	= '/data/celldepot/all_CSC_h5ad/';



$BXAF_CONFIG_CUSTOM['CELLXGENE_URL']					= 'http://celldepot.example.com';  //No slash at the end
$BXAF_CONFIG_CUSTOM['CELLXGENE_PORT_START']				= 8100;
$BXAF_CONFIG_CUSTOM['CELLXGENE_PORT_END']				= 8199;
$BXAF_CONFIG_CUSTOM['CELLXGENE_checkCSCh5ad']			= '/data/celldepot/bin/checkCSCh5ad';
$BXAF_CONFIG_CUSTOM['CELLXGENE_getH5adInfo']			= '/data/celldepot/bin/getH5adInfo';
$BXAF_CONFIG_CUSTOM['CELLXGENE_plotH5ad']				= '/data/celldepot/bin/plotH5ad';
$BXAF_CONFIG_CUSTOM['CELLXGENE_toCSCh5ad']				= '/data/celldepot/bin/toCSCh5ad';






function custom_get_cellxgene_launch_command($port = 0, $file = ''){
	
	$command = '';
	$command .= "cd  /data/celldepot/single_cell_portal/cellxgene_instances/" . "\n";
	$command .= ". '/data/anaconda3/etc/profile.d/conda.sh'" . "\n";
	$command .= "conda activate cellxgene" . "\n";
	$command .= "cellxgene launch {$file} --host 0.0.0.0 -p {$port} --disable-annotations --max-category-items 500" . "\n";


	return $command;

}

function custom_get_cellxgene_documation(){
	
	$doc = '
<br/>


<h3>Standard Launching (One Cellxgene VIP Instance Shared by All Projects)</h3>
<pre>
cd /data/celldepot/celldepot/all_h5ad_logs/

. "/data/anaconda3/etc/profile.d/conda.sh"
conda activate VIP_py3.8_pandoc
nohup cellxgene launch  --dataroot /data/celldepot/all_h5ad_files/ --backed --host 0.0.0.0 -p 10000 --disable-annotations > cellxgene_dataroot_8116.log &

##To kill a running process
echo "$(ps -fu share)"
#kill process if needed
</pre>


<br/><hr/><br/>


<h3>Preload in Memory (One Cellxgene VIP Instance per Project)</h3>
<pre>
cd /data/celldepot/single_cell_portal/cellxgene_instances/
. "/data/anaconda3/etc/profile.d/conda.sh"
conda activate cellxgene
cellxgene launch {h5ad_file} --host 0.0.0.0 -p {port} --disable-annotations --max-category-items 500
</pre>
';


	return $doc;

}


?>