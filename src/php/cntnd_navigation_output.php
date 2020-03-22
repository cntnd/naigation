<?php
// cntnd_navigation_output

// assert framework initialization
defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

// editmode
$editmode = cRegistry::isBackendEditMode();

// input/vars
$category_id = (int) "CMS_VALUE[1]";
if (empty($category_id)){
    $category_id=1;
}

$template = "CMS_VALUE[2]";
if (empty($template)){
  $template='default.html';
}

$subnav = "CMS_VALUE[3]";
$subnav_depth = (int) "CMS_VALUE[4]";
if (empty($subnav_depth)){
  $subnav_depth = 4;
}

// module
if ($editmode){
	echo '<div class="content_box"><label class="content_type_label">'.mi18n("MODULE").'</label>';
}

// get client settings
$rootIdcat = getEffectiveSetting('navigation_main', 'idcat', $category_id);
$depth = getEffectiveSetting('navigation_main', 'depth', $subnav_depth);

// get category tree
$categoryHelper = cCategoryHelper::getInstance();
$categoryHelper->setAuth(cRegistry::getAuth());
$tree = $categoryHelper->getSubCategories($rootIdcat, $depth);

// get path (breadcrumb) of current category
$filter = create_function('cApiCategoryLanguage $item', 'return $item->get(\'idcat\');');
$path = array_map($filter, $categoryHelper->getCategoryPath(cRegistry::getCategoryId(), $category_id));

// redirects
$db = new cDb;
$db->query("SELECT * FROM %s ",$cfg['tab']['art_lang'],$client);
$redirect = array();

while ($db->nextRecord()) {
	$redirect[$db->f('idartlang')]=array(
		"redirect" => $db->f('redirect'),
		"redirect_url" => $db->f('redirect_url')
	);
}

// display navigation
$smarty = cSmartyFrontend::getInstance();
$smarty->assign('ulId', 'navigation');
$smarty->assign('tree', $tree);
$smarty->assign('path', $path);
$smarty->assign('redirect', $redirect);
$smarty->assign('subnav', $subnav);
$smarty->display($template);

if ($editmode){
  echo '</div>';
}
?>
