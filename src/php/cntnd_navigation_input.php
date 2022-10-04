?><?php
// cntnd_navigation_input
$cntnd_module = "cntnd_navigation";

// includes
cInclude('module', 'includes/style.cntnd_navigation_input.php');
cInclude('module', 'includes/class.cntnd_navigation.php');
echo '<script src="https://cdn.jsdelivr.net/gh/cntnd/core_style@1.1.1/dist/core_script.min.js"></script>';


// input/vars
$category_id = (int) "CMS_VALUE[1]";
if (empty($category_id)){
    $category_id=1;
}
$template = "CMS_VALUE[2]";
if (!Cntnd\Navigation\CntndNavigation::isTemplate($cntnd_module, $client, $template)){
    $template="default.html";
}

// other/vars
$uuid = rand();
$templates = Cntnd\Navigation\CntndNavigation::templates($cntnd_module, $client);

if (!$template OR empty($template) OR $template=="false"){
    echo '<div class="cntnd_info cntnd_info-primary">'.mi18n("CHOOSE_TEMPLATE").'</div>';
}
?>
<div class="form-vertical d-flex w-50">
    <div class="form-group w-100">
        <label><?= mi18n("SELECT_CATEGORY") ?></label>
        <?php echo buildCategorySelect("CMS_VAR[1]", $category_id); ?>
    </div>
    <div class="form-group w-100">
        <label for="template_<?= $uuid ?>"><?= mi18n("TEMPLATE") ?></label>
        <select name="CMS_VAR[2]" id="template_<?= $uuid ?>" size="1">
            <option value="false"><?= mi18n("SELECT_CHOOSE") ?></option>
            <?php
            foreach ($templates as $template_file) {
                $selected="";
                if ($template==$template_file){
                    $selected = 'selected="selected"';
                }
                echo '<option value="'.$template_file.'" '.$selected.'>'.$template_file.'</option>';
            }
            ?>
        </select>
    </div>
    <fieldset>
        <legend><?= mi18n("SUBNAV") ?></legend>
        <div class="form-check form-check-inline">
            <input id="subnav_<?= $uuid ?>" class="form-check-input" type="checkbox" name="CMS_VAR[3]" value="true" <?php if("CMS_VALUE[3]"=='true'){ echo 'checked'; } ?> />
            <label for="subnav_<?= $uuid ?>"><?= mi18n("SHOW_SUBNAV") ?></label>
        </div>

        <div class="form-check form-check-inline">
            <input id="static_subnav_<?= $uuid ?>" class="form-check-input check-dependent" data-check-dependent="subnav_<?= $uuid ?>" type="checkbox" name="CMS_VAR[5]" value="true" <?php if("CMS_VALUE[5]"=='true'){ echo 'checked'; } ?> />
            <label for="static_subnav_<?= $uuid ?>"><?= mi18n("STATIC_SUBNAV") ?></label>
        </div>
    </fieldset>
</div>
<?php
