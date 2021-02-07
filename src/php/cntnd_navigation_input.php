?><?php
// cntnd_navigation_input

// includes
cInclude('module', 'includes/style.cntnd_navigation_input.php');
cInclude('module', 'includes/script.cntnd_navigation_input.php');

// input/vars
$category_id = (int) "CMS_VALUE[1]";
if (empty($category_id)){
    $category_id=1;
}
$template = "CMS_VALUE[2]";

// other/vars
$uuid = rand();
$templateOptions= array();
$template_dir   = $cfgClient[$client]["module"]["path"].'cntnd_navigation/template/';
$handle         = opendir($template_dir);
while ($entryName = readdir($handle)){
    if (is_file($template_dir.$entryName)){
        $selected="";
        if ($template==$template_dir.$entryName){
            $selected = 'selected="selected"';
        }
        $templateOptions[]='<option '.$selected.' value="'.$template_dir.$entryName.'">'.$entryName.'</option>';
    }
}
closedir($handle);
asort($templateOptions);

$db=cRegistry::getDb();
$sql = "SELECT DISTINCT dirname from ".$cfg["tab"]["upl"];
$db->query($sql);
while ( $db->nextRecord() ) {
    $dirs[] = $db->f("dirname");
}

if (!$template OR empty($template) OR $template=="false"){
    echo '<div class="cntnd_info cntnd_info-primary">'.mi18n("CHOOSE_TEMPLATE").'</div>';
}
?>
<div class="form-vertical">
    <div class="form-group">
        <label><?= mi18n("SELECT_CATEGORY") ?></label>
        <?php echo buildCategorySelect("CMS_VAR[1]", $category_id); ?>
    </div>

    <div class="form-check">
        <input id="subnav_<?= $uuid ?>" class="form-check-input" type="checkbox" name="CMS_VAR[3]" value="true" <?php if("CMS_VALUE[3]"=='true'){ echo 'checked'; } ?> />
        <label for="subnav_<?= $uuid ?>"><?= mi18n("SHOW_SUBNAV") ?></label>
    </div>

    <div class="form-check">
        <input id="static_subnav_<?= $uuid ?>" class="form-check-input check-dependent" data-check-dependent="subnav_<?= $uuid ?>" type="checkbox" name="CMS_VAR[5]" value="true" <?php if("CMS_VALUE[5]"=='true'){ echo 'checked'; } ?> />
        <label for="static_subnav_<?= $uuid ?>"><?= mi18n("STATIC_SUBNAV") ?></label>
    </div>

    <div class="form-group">
        <label for="template_<?= $uuid ?>"><?= mi18n("TEMPLATE") ?></label>
        <select name="CMS_VAR[2]" id="template_<?= $uuid ?>" size="1">
            <option value="false"><?= mi18n("SELECT_CHOOSE") ?></option>
            <?php
            foreach ($templateOptions as $value) {
                echo $value;
            }
            ?>
        </select>
    </div>
</div>
<?php
