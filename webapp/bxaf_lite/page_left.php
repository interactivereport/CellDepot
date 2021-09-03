<?php

/*
$BXAF_CONFIG['LEFT_MENU_ITEMS'] = array(
    'Category1'=>array(
        'Title'     =>  'Internal Links',
        'Icon'      =>  'fa-sliders-h',
        'Children'  =>  array(
            array(
                'Title' =>  'Blank Page',
                'Icon'  =>  'fa-database',
                'Barcode'  =>  'Blank Page',
                'URL'   =>  '/'. $BXAF_CONFIG['BXAF_APP_SUBDIR'] . '_blank.php',
            ),
            array(
                'Title' =>  'Example 1',
                'Icon'  =>  'fa-user',
                'Barcode'  =>  'Barcode1',
                'URL'   =>  '/'. $BXAF_CONFIG['BXAF_APP_SUBDIR'] . 'example1.php',
            ),
            array(
                'Title' =>  'Example 2',
                'Icon'  =>  '',
                'URL'   =>  'example2.php',
            ),
        ),
    ),
    'Category2'=>array(
        'Title'     =>  'External Links',
        'Icon'      =>  'fa-pie-chart',
        'Children'  =>  array(
            array(
                'Title' =>  'Yahoo',
                'Icon'  =>  '',
                'URL'   =>  'http://yahoo.com/',
            ),
            array(
                'Title' =>  'Google',
                'Icon'  =>  '',
                'URL'   =>  'http://google.com/',
            ),
            array(
                'Title' =>  'BioInfoRx',
                'Icon'  =>  '',
                'URL'   =>  'https://bioinforx.com/',
            ),
        ),
    ),
    'Category3'=>array(
        'Title'     =>  'Link with Parameters',
        'Icon'      =>  'fa-sliders-h',
        'Children'  =>  array(
            array(
                'Title' =>  'example3.php?id=1',
                'Icon'  =>  '',
                'URL'   =>  '/'. $BXAF_CONFIG['BXAF_APP_SUBDIR'] . 'example3.php?id=1',
            ),
            array(
                'Title' =>  'example3.php?id=2',
                'Icon'  =>  '',
                'URL'   =>  'example3.php?id=2',
            ),
            array(
                'Title' =>  'example3.php?id=3',
                'Icon'  =>  '',
                'URL'   =>  $BXAF_CONFIG['BXAF_WEB_URL'] . 'example3.php?id=3',
            ),
        ),
    ),
);
*/



if(isset($BXAF_CONFIG['LEFT_MENU_ITEMS']) && is_array($BXAF_CONFIG['LEFT_MENU_ITEMS']) && count($BXAF_CONFIG['LEFT_MENU_ITEMS']) > 0){

    $current_page = rtrim($BXAF_CONFIG['BXAF_ROOT_URL'], '/') . $_SERVER['REQUEST_URI'];
    $reference_url = $BXAF_CONFIG['BXAF_URL'];

    echo "<div id='bxaf_page_left' class='" . $BXAF_CONFIG['BXAF_PAGE_CSS_LEFT'] . "'>";

        echo "<ul class='list-group'>";

        foreach($BXAF_CONFIG['LEFT_MENU_ITEMS'] as $currentCategory => $currentCategoryDetails){

            $categoryIsActive = false;
			
			if (isset($BXAF_CONFIG['LEFT_MENU_ITEMS'][$currentCategory]['expanded'])){
				$categoryIsActive = $BXAF_CONFIG['LEFT_MENU_ITEMS'][$currentCategory]['expanded'];
			}
			
			
            if (isset($PAGE['Category']) && $currentCategory == $PAGE['Category']) $categoryIsActive = true;

            if (sizeof($currentCategoryDetails['Children']) > 0){

                foreach($currentCategoryDetails['Children'] as $tempKey => $currentAppDetails){

                    if(is_array($currentAppDetails) && array_key_exists('URL', $currentAppDetails)){

                        if($currentAppDetails['URL'] == '') $currentAppDetails['URL'] = '#';
                        else $currentAppDetails['URL'] = bxaf_absolute_url($currentAppDetails['URL'], $reference_url);

                        $currentCategoryDetails['Children'][$tempKey]['URL'] = $currentAppDetails['URL'];

                        if(strpos($current_page, $currentAppDetails['URL']) === 0) $categoryIsActive = true;
                    }
                    else {
                        $currentCategoryDetails['Children'][$tempKey]['URL'] = '#';
                    }
                }
            }

            echo "<li class='list-group-item " . ($categoryIsActive ? "" : "") . "'>";

                $url = '#';
                $icon = '';
                $title = '';

                if(isset($currentCategoryDetails['URL']) && $currentCategoryDetails['URL'] != '') $url = $currentCategoryDetails['URL'];
                if(isset($currentCategoryDetails['Icon']) && $currentCategoryDetails['Icon'] != '') $icon = $currentCategoryDetails['Icon'];
                if(isset($currentCategoryDetails['Title']) && $currentCategoryDetails['Title'] != '') $title = $currentCategoryDetails['Title'];

                echo "<a class='" . ($categoryIsActive ? "font-weight-bold" : "") . "' href='$url' onClick=\"\$(this).next().toggle();\">";
                    echo "<i class='fas fa-fw " . ($icon == '' ? ($categoryIsActive ? "fa-dot-circle" : "fa-circle") : $icon) . "' aria-hidden='true'></i> ";
                    echo htmlentities( $title, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                echo "</a>";


                if (sizeof($currentCategoryDetails['Children']) > 0){

                    echo "<ul class='fa-ul w-100 my-2 mx-2 " . ($categoryIsActive ? "" : "hidden") . "'>";

                    foreach($currentCategoryDetails['Children'] as $tempKey => $currentAppDetails){

                        $url = '#';
                        $icon = '';
                        $title = '';

                        if(isset($currentAppDetails['URL']) && $currentAppDetails['URL'] != '') $url = $currentAppDetails['URL'];
                        if(isset($currentAppDetails['Icon']) && $currentAppDetails['Icon'] != '') $icon = $currentAppDetails['Icon'];
                        if(isset($currentAppDetails['Title']) && $currentAppDetails['Title'] != '') $title = $currentAppDetails['Title'];

                        $currentAppActive = false;
                        if (strpos($current_page, $url) === 0 || (isset($PAGE['Category']) && $currentCategory == $PAGE['Category'] && isset($PAGE['Barcode']) && isset($currentAppDetails['Barcode']) && $currentAppDetails['Barcode'] == $PAGE['Barcode']) ){
                            $currentAppActive = true;
                        }

                        echo "<li class='p-1 " . ($currentAppActive ? "table-info" : "") . "'>";
                            echo "<a class='" . ($currentAppActive ? "" : "") . "' href='$url' " . (strpos($url, $reference_url) === 0 ? "" : " target='_blank' ") . ">";
                                echo "<i class='fa-fw " . ($icon == '' ? ($currentAppActive ? "fas fa-circle" : "far fa-circle") : $icon) . "' aria-hidden='true'></i> ";
                                echo htmlentities($title, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            echo "</a>";
                        echo "</li>";
                    }

                    echo "</ul>";
                }
            echo "</li>";
        }

        echo "</ul>";

    echo "</div>";
}


?>