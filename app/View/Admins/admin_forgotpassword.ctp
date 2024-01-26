<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#adminForgotpassword").validate();
        
    });
</script>
<div class="page">
    <div class="main">
        <div class="accountInfo">
            <div class="login">
                <div class="login_h1"><span id="lblTitle">Forgot Password</span></div>
                <?php
                if ($this->Session->check('error_msg')) {
                    echo "<div class='ActionMsgBox error' id='msgID'  style='width:100%; margin:0 0 5px 0;'><ul><li>" . $this->Session->read('error_msg') . "</li></ul></div>";
                    $this->Session->delete("error_msg");
                } elseif ($this->Session->check('success_msg')) {
                    echo "<div class='SuccessMsgBox success' id='msgID'  style='width:100%; margin:0 0 5px 0;'><ul><li>" . $this->Session->read('success_msg') . "</li></ul></div>";
                    $this->Session->delete("success_msg");
                }
                ?>
                <?php
                echo $this->Form->create('Admin', array('enctype' => 'multipart/form-data', 'name' => 'adminForgotpassword', 'id' => 'adminForgotpassword', 'class' => 'form with-margin'));
                ?><input type="hidden" name="a" id="a" value="send">
                <div class="field">
                    <label for="UserName" id="UserNameLabel">Enter Email:</label>
                    <div class="boxRound">
                        <?php echo $this->Form->input('email', array('size' => '25', 'id' => 'recovery-mail', 'label' => false, 'div' => false, 'class' => "full-width email required")); ?>

                    </div>
                </div>
                <div class="submitButton">
                    <?php echo $this->Form->submit('Submit', array('class' => 'big_button', 'maxlength' => '50', 'size' => '30', 'label' => '', 'div' => false)) ?>


                    <span class="fot_dv">
                        <?php echo $this->Html->link('Login', array('controller' => 'admins', 'action' => 'login'), array('class' => 'submit_btn float-right')); ?>
                    </span>

                </div>



                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>


