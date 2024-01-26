
<script type="text/javascript">
    $(document).ready(function() {
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s`~!@#$%^&*()+={}|;:'",.\/?\\-]+$/.test(value);
        }, "Please do not enter  special character like < or >");
        $("#sendEmail").validate();
    });
</script>
<?php
echo $this->element("admin_menu");
$this->Html->addCrumb('Dashboard', '/admin/admins');
$this->Html->addCrumb('Configuration ', 'javascript:void(0)');
$this->Html->addCrumb('Send Email', 'javascript:void(0);');
?>
<div class="ful_wdith right_con">
    <div class="wht_bg">
        <h2 class="dashboard png">Send Email</h2>

        <div class="columns mrgih_tp">
            <fieldset>
                <legend>Send Email</legend>

                <?php echo $this->Form->create('User', array('method' => 'POST', 'name' => 'sendEmail', 'enctype' => 'multipart/form-data', 'id' => 'sendEmail')); ?>
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
                        <label for="new-password">Email Address <span class="require">*</span></label>
                        <span class="relative">
                            <?php echo $this->Form->text('User.email_address', array('maxlength'=>'255','size' => '25', 'label' => '', 'div' => false, 'class' => "full-width required email")) ?>
                        </span>
                    </p>
                </div>         
                <div class="columns">
                    <p class="colx2-left">
                        <label for="new-password">User Name <span class="require"></span></label>
                        <span class="relative">
                            <?php echo $this->Form->text('User.name', array('maxlength'=>'255','size' => '25', 'label' => '', 'div' => false, 'class' => "full-width")) ?>
                        </span>
                    </p>
                </div>         
                <div class="columns">
                    <p class="colx2-left">
                        <label for="new-password">Subject <span class="require">*</span></label>
                        <span class="relative">
                            <?php echo $this->Form->text('User.subject', array('maxlength'=>'255','size' => '25', 'label' => '', 'div' => false, 'class' => "full-width required")) ?>
                        </span>
                    </p>
                </div>         
                <div class="columns">
                    <p class="colx2-left">
                        <label for="new-password">Message <span class="require">*</span></label>
                        <span class="relative">
                            <?php
                            echo $this->Form->textarea(
                                    'User.message', array('rows' => '5', 'cols' => '5', 'class' => 'full-width required ')
                            );
                            ?>
                        </span>
                    </p>
                </div>
            </fieldset>
            <fieldset class="grey-bg no-margin">
                <p class="input-with-button margin-top">
                    <?php echo $this->Form->submit('save.png', array('size' => '30', 'label' => '', 'div' => false, 'type' => 'submit')); ?>
                    <?php echo $this->Form->reset('', array('size' => '30', 'label' => '', 'div' => false, 'type' => 'reset','value'=>'')); ?>
                </p>
            </fieldset>
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="clr"></div>
    </div>
</div>