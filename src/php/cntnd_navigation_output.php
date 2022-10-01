<?php
// cntnd_navigation_output

// includes
cInclude('module', 'includes/class.cntnd_navigation.php');

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
if (!Cntnd\Navigation\CntndNavigation::isTemplate('cntnd_navigation', $client, $template)){
    $template="default.html";
}

$subnav = "CMS_VALUE[3]";
$subnav_depth = (int) "CMS_VALUE[4]";
if (empty($subnav_depth)){
    $subnav_depth = 4;
}
$static_subnav = "CMS_VALUE[5]";

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

if (!function_exists("navigation")){
    function navigation($tree):array {
        $nav=array();
        $subcats=array();
        foreach ($tree as $wrapper) {
            if ($wrapper['item']->get('startidartlang')!="0") {
                if (is_array($wrapper['subcats'])) {
                    $subcats = navigation($wrapper['subcats']);
                }

                $nav[$wrapper['item']->get('startidartlang')] = array(
                    "idcat" => $wrapper['idcat'],
                    "redirect" => false,
                    "idartlang" => $wrapper['item']->get('startidartlang'),
                    "url" => $wrapper['item']->getLink(),
                    "target" => "_self",
                    "name" => $wrapper['item']->get('name'),
                    "subcats" => $subcats
                );
            }
        }
        return $nav;
    }
}

$navigation = navigation($tree);
$path = $categoryHelper->getParentCategoryIds($idcat, $depth);
$path[]=$idcat;

// redirects
$db = new cDb;
$db->query("SELECT * FROM %s WHERE online = 1 AND redirect = 1",$cfg['tab']['art_lang'],$client);
while ($db->nextRecord()) {
    $target = "_blank";
    // startsWith(redirect_url, frontend_url);
    // (https://gist.github.com/umidjons/10094793)
    if (strncmp($db->f('redirect_url'), cRegistry::getFrontendUrl(), strlen(cRegistry::getFrontendUrl())) === 0){
        $target="_self";
    }

    if (array_key_exists($db->f('idartlang'), $navigation)){
        $navigation[$db->f('idartlang')]["redirect"]=$db->f('redirect');
        $navigation[$db->f('idartlang')]["url"]=$db->f('redirect_url');
        $navigation[$db->f('idartlang')]["target"]=$target;
    }
}

// display navigation
$smarty = cSmartyFrontend::getInstance();
$smarty->assign('ulId', 'navigation');
$smarty->assign('path', $path);
$smarty->assign('current', $idcat);
$smarty->assign('navigation', $navigation);
$smarty->assign('subnav', $subnav);
$smarty->assign('staticSubnav', $static_subnav);
$smarty->display($template);

if ($editmode){
    echo '</div>';
}
?>
