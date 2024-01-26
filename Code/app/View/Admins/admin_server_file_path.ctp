<?php
/*
 * admin_index file
 */
?>

<?php
echo $this->element("admin_menu");
$this->Html->addCrumb('Dashboard', '/admin/admins');
$this->Html->addCrumb('Configuration ', 'javascript:void(0)');
$this->Html->addCrumb('Server Configuration Path', 'javascript:void(0);');
?>
<div class="ful_wdith right_con">
    <div class="wht_bg fil_wd">
        <h2 class="dashboard png">Server Configuration Path</h2>
       

        <?php echo $this->element("admin/admin/server_file_path"); ?>
