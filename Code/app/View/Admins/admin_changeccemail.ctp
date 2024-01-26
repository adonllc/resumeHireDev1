<script type="text/javascript">
    $(document).ready(function(){
        $("#adminChamheEmail").validate();
    });
</script>

<?php
echo $this->element("admin_menu");
$this->Html->addCrumb('Dashboard', '/admin/admins');
$this->Html->addCrumb('Configuration ', 'javascript:void(0)');
$this->Html->addCrumb('Change CC Email', 'javascript:void(0)');
?>

<div class="ful_wdith right_con">
    <div class="wht_bg">
        <h2 class="dashboard png">Change CC Email</h2>

        <div class="columns mrgih_tp">
            <fieldset>
                <legend>Change Admin CC Email</legend>

                <?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'adminChamheEmail', 'enctype' => 'multipart/form-data', 'id' => 'adminChamheEmail')); ?>
                <span class="require" style="float: left;width: 100%;">* Please note that all fields that have an asterisk (*) are required. </span>
                <?php
                if ($this->Session->check('error_msg')) {
                    echo "<div class='ActionMsgBox error' id='msgID'><ul><li>" . $this->Session->read('error_msg') . "</li></ul></div>";
                    $this->Session->delete("error_msg");
                } elseif ($this->Session->check('success_msg')) {
                    echo "<div class='SuccessMsgBox success' id='msgID'><ul><li>" . $this->Session->read('success_msg') . "</li></ul></div>";
                    $this->Session->delete("success_msg");
                }
                ?>
                <div class="columns">
                    <p class="colx2-left">
                        <label for="old-password">Old CC Email </label>
                       <div class="form-control">
                            <?php echo $Admins['Admin']['cc_email']; ?> 
                       </div>
                    </p>
                </div>
                <div class="columns">
                    <p class="colx2-left">
                        <label for="new-password">New CC Email <span class="require">*</span></label>
                        <span class="relative">
                            <?php echo $this->Form->text('Admin.new_email', array('size' => '25', 'label' => '', 'div' => false, 'class' => "full-width email required")) ?>
                        </span>
                    </p>
                </div>
                <div class="columns">
                    <p class="colx2-left">
                        <label for="retype-new-password">Confirm CC Email <span class="require">*</span></label>
                        <span class="relative">
                            <?php echo $this->Form->text('Admin.conf_email', array('size' => '25', 'label' => '', 'div' => false, 'class' => 'full-width required email')) ?>
                        </span>
                    </p>
                </div>
            </fieldset>
            <fieldset class="grey-bg no-margin">
                <?php
                echo $this->Form->input('Admin.id', array('type' => 'hidden', 'value' => $Admins['Admin']['id']));
                echo $this->Form->input('Admin.old_email', array('type' => 'hidden', 'value' => $Admins['Admin']['cc_email']));
                ?>
                <p class="input-with-button margin-top">
                    <?php echo $this->Form->submit('save.png', array('size' => '30', 'label' => '', 'div' => false, 'type' => 'submit')); ?>
                </p>
            </fieldset>
            <?php echo $this->Form->end(); ?>
        </div>

        <div class="clr"></div>
    </div>
</div>
