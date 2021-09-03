<?php

/*
//Example of customized menu


$BXAF_CONFIG['PAGE_MENU_ITEMS'] =  array(
    array(
        'Name'=>'Template',
        'URL' => '/'. $BXAF_CONFIG['BXAF_APP_SUBDIR'] . '_blank.php',
    ),
	array(
		'Name'=>'Example1',
		'URL' => '/'. $BXAF_CONFIG['BXAF_APP_SUBDIR'] . 'example1.php',
	),
	array(
		'Name'=>'Example2',
		'URL'=> '/'. $BXAF_CONFIG['BXAF_APP_SUBDIR'] . 'example2.php',
	),
	array(
		'Name'=>'Links',
		'Children'=>array(
            array(
                'Name'=>'BioInfoRx Links',
                'Class'=>'dropdown-header',
            ),
            array(
                'Name'=>'Products',
                'URL'=>'https://bioinforx.com/lims2/products.php',
            ),
            array(
                'Name'=>'Contact',
                'URL'=>'https://bioinforx.com/lims2/contact.php',
            ),
            array(
                'Class'=>'divider',
            ),
            array(
                'Name'=>'Other Links',
                'Class'=>'dropdown-header',
            ),
            array(
                'Name'=>'Google',
                'URL'=>'https://www.google.com/',
            ),
		),
	),
);

*/

?>


<style>
/* space for top menu bar */
    body {
        padding-top: 3.5rem;
    }
</style>

<nav id='navigation-bar' class="navbar navbar-expand-lg fixed-top <?php echo $BXAF_CONFIG['BXAF_PAGE_CSS_MENU']; ?>">

    <a class="navbar-brand" href="/<?php echo $BXAF_CONFIG['BXAF_APP_SUBDIR']; ?>">
    	<?php
            if ($BXAF_CONFIG['BXAF_PAGE_APP_LOGO_URL'] != ''){
                echo "<img src='{$BXAF_CONFIG['BXAF_PAGE_APP_LOGO_URL']}' style='img-fluid'/> ";
            }
            else if ($BXAF_CONFIG['BXAF_PAGE_APP_ICON_CLASS'] != ''){
                echo "<i class='{$BXAF_CONFIG['BXAF_PAGE_APP_ICON_CLASS']}'></i> ";
            }

            if($BXAF_CONFIG['BXAF_PAGE_APP_NAME_SHOW']) echo $BXAF_CONFIG['BXAF_PAGE_APP_NAME'];
		?>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">

        <?php

        	$bxaf_menu_items = array();
        	if(isset($BXAF_CONFIG['PAGE_MENU_ITEMS']) && is_array($BXAF_CONFIG['PAGE_MENU_ITEMS']) && count($BXAF_CONFIG['PAGE_MENU_ITEMS']) > 0){
        		$bxaf_menu_items = $BXAF_CONFIG['PAGE_MENU_ITEMS'];
        	}
        	else if(isset($BXAF_CONFIG['PAGE_MENU_ITEMS']) && is_array($BXAF_CONFIG['PAGE_MENU_ITEMS']) && count($BXAF_CONFIG['PAGE_MENU_ITEMS']) > 0){
        		$bxaf_menu_items = $BXAF_CONFIG['PAGE_MENU_ITEMS'];
        	}

        	if (isset($bxaf_menu_items) && is_array($bxaf_menu_items) && count($bxaf_menu_items) > 0){

        		$current_page = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        		echo "<ul id='bxaf_page_menu_left' class='navbar-nav mr-auto'>";

        		foreach($bxaf_menu_items as $key=>$menu_item){
        			if (! isset($menu_item['Children']) || ! is_array($menu_item['Children']) || count($menu_item['Children']) <= 0){
        				$real_url = bxaf_absolute_url($menu_item['URL'], $current_page);

        				$active_class = '';
        				if ($real_url == $current_page) $active_class = "active";

						unset($icon);
						if ($menu_item['Icon'] != ''){
							$icon = "<i class='fas fa-fw {$menu_item['Icon']}' aria-hidden='true'></i> ";
						}

						$URL_Name = htmlentities($menu_item['Name'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
						$URL_Class = $menu_item['Class'];

                        $_blank = (strpos($menu_item['URL'], $BXAF_CONFIG['BXAF_ROOT_URL']) === 0 || (! preg_match("/^http/", $menu_item['URL'])) ) ? "" : "target='_blank'";
        				echo "<li class='nav-item {$active_class}'><a class='nav-link {$URL_Class}' href='{$menu_item['URL']}' {$_blank}>{$icon}{$URL_Name}</a></li>";
        			}

        			else if(is_array($menu_item['Children']) && count($menu_item['Children']) > 0){

        				$active_subclass = array();
        				foreach($menu_item['Children'] as $subkey=>$submenu_item){
        					if(isset($submenu_item['URL']) && $submenu_item['URL'] != ''){
        						$real_url = bxaf_absolute_url($submenu_item['URL'], $current_page);
        						if ($real_url == $current_page) $active_subclass[$subkey] = 'active';
        					}
                            else $active_subclass[$subkey] = '';
        				}

        				$active_class = '';
        				if (is_array($active_subclass) && count($active_subclass) > 0) $active_class = 'active';

        				echo "<li class='nav-item dropdown " . $active_class . "'>";

        					echo "<a class='nav-link dropdown-toggle' href='#' id='bxaf_page_menu_$key' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" . htmlentities($menu_item['Name'], ENT_QUOTES | ENT_IGNORE, "UTF-8") . "</a>";

        					echo "<div class='dropdown-menu' aria-labelledby='bxaf_page_menu_$key'>";

        					foreach($menu_item['Children'] as $subkey=>$submenu_item){

        						$active_class = '';
        						if(array_key_exists($subkey, $active_subclass) ) $active_class = $active_subclass[$subkey];

        						if(isset($submenu_item['URL']) && $submenu_item['URL'] != '' && isset($submenu_item['Name']) && $submenu_item['Name'] != ''){
                                    $_blank = (strpos($submenu_item['URL'], $BXAF_CONFIG['BXAF_ROOT_URL']) === 0 || (! preg_match("/^http/", $submenu_item['URL'])) ) ? "" : "target='_blank'";
                                    $icon = ($submenu_item['Icon'] != '') ? "<i class='fas fa-fw {$submenu_item['Icon']}' aria-hidden='true'></i>" : "<i class='fas fa-fw fa-caret-right' aria-hidden='true'></i>";

                                    echo "<a class='dropdown-item " . $active_class . "' href='" . $submenu_item['URL'] . "' {$_blank}>{$icon} " . htmlentities($submenu_item['Name'], ENT_QUOTES | ENT_IGNORE, "UTF-8") . "</a>";
        						}
        						else if(isset($submenu_item['Class']) && $submenu_item['Class'] != '' && isset($submenu_item['Name']) && $submenu_item['Name'] != ''){
        							echo "<a class='dropdown-item " . $submenu_item['Class'] ."'>" . htmlentities($submenu_item['Name'], ENT_QUOTES | ENT_IGNORE, "UTF-8") . " </a>";
        						}
        						else if(isset($submenu_item['Class']) && $submenu_item['Class'] == 'divider'){
        							echo "<hr class='my-2' />";
        						}
                                else if(isset($submenu_item['Class']) && $submenu_item['Class'] != ''){
        							echo "<a class='dropdown-item " . $submenu_item['Class'] . " </a>";
        						}

        					}

        					echo "</div>";

        				echo "</li>";

        			}

        		}

        		echo "</ul>";
        	} //if(isset($bxaf_menu_items) && is_array($bxaf_menu_items) && count($bxaf_menu_items) > 0){


            if(isset($BXAF_CONFIG['BXAF_PAGE_SPLIT']) && $BXAF_CONFIG['BXAF_PAGE_SPLIT']){
                echo '<a class="nav-link" href="Javascript: void(0);" onClick="if($(\'#bxaf_page_left\').hasClass(\'hidden\')) { $(\'#bxaf_page_right\').removeClass(\'w-100 d-flex align-content-between flex-wrap\'); $(\'#bxaf_page_right\').addClass(\'' . $BXAF_CONFIG["BXAF_PAGE_CSS_RIGHT"] . '\'); $(\'#bxaf_page_left\').removeClass(\'hidden\'); } else { $(\'#bxaf_page_left\').addClass(\'hidden\'); $(\'#bxaf_page_right\').removeClass(\'' . $BXAF_CONFIG["BXAF_PAGE_CSS_RIGHT"] . '\'); $(\'#bxaf_page_right\').addClass(\'w-100 d-flex align-content-between flex-wrap\'); } " title="Toggle left panel"><i class="fas fa-columns"></i></a>';
            }

      		if(isset($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) && $_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]){
                if(isset($_SESSION['User_Info']['Name']) && $_SESSION['User_Info']['Name'] != ''){
                    echo '<a class="nav-link" href="' . $BXAF_CONFIG['BXAF_USER_PROFILE'] . '">Hello, ' . $_SESSION['User_Info']['Name'] . '</a>';
          		}
      			echo '<a class="nav-link" href="' . $BXAF_CONFIG['BXAF_LOGOUT_PAGE'] . '"><i class="fas fa-sign-out-alt"></i> Sign Out</a>';
      		}
			else {
				echo '<a class="nav-link" href="' . $BXAF_CONFIG['BXAF_LOGIN_PAGE'] . '"><i class="fas fa-sign-in-alt"></i> Sign In</a>';
			}
      	?>

    </div>

</nav>