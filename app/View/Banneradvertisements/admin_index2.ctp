<?php
$this->Html->addCrumb('Dashboard » ', '/admin/admins');
$this->Html->addCrumb('Banner Advertisements Management » ','/admin/banneradvertisements');
$this->Html->addCrumb('List Banner Advertisements');
?>	
<div class="row">
    <div class="col-md-12">
        <?php
        if ($this->Session->check('error_msg')) {
            echo "<div class='ActionMsgBox error' id='msgID'><ul><li>" . $this->Session->read('error_msg') . "</li></ul></div>";
            $this->Session->delete("error_msg");
        } elseif ($this->Session->check('success_msg')) {
            echo "<div class='SuccessMsgBox success' id='msgID'><ul><li>" . $this->Session->read('success_msg') . "</li></ul></div>";
            $this->Session->delete("success_msg");
        }
        ?>
 
        <div class="columns mrgih_tp new_dvdf" >
            <?php echo $this->Form->create("Banneradvertisement", array("url" => "index", "method" => "Post")); ?>
            <div class="columns kmw">
                <p class="colx2-left">
                    <label for="old-password">Search Banner Advertisements by typing title </label>
                    <span class="relative">       
                        <?php echo $this->Form->text('Banneradvertisement.bannerName', array('value' => $bannerName)); ?>
                    </span>
                </p>
            </div>
          
            <fieldset class="outside mroig">
                <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success btn-cons')); ?>
               <?php echo $this->Html->link('Clear Filter', array('controller' => 'banneradvertisements', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-cons')); ?>
            </fieldset>
            <?php echo $this->Form->end(); ?>
            <div class="rt_float">
            	<?php echo $this->Html->link('<span class="icon-plus-circled col4">Add Banner</span>', array('controller' => 'banneradvertisements', 'action' => 'addBanneradvertisement'), array('title'=>'Add Banner', 'indicator' => 'loaderID', 'class' => 'custom_link', 'escape' => false));?>
            </div>
        </div>
        <?php echo $this->element('admin/banneradvertisements/index'); ?>
    </div>
</div>		
<!-- END PAGE --> 






