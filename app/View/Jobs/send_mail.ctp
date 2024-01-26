
<script>
    $().ready(function () {
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9-]+$/.test(value));
        }, "Contact Number is not valid.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*Note: Special characters, number and spaces are not allowed.");
        $("#sendEmailMessage").validate();
    });
</script>
<div class="rght_dv">

    <div class="info_dv">
        <div class="heads">Send Email</div>
        <div class="information_cntn">

            <?php echo $this->Form->create("Job", array('url' => 'sendMail/' . $jobInfo['Job']['slug'], 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'sendEmailMessage', 'name' => 'sendEmailMessage')); ?>
            <div class="form_lst">
                <?php
                $emails = $_SESSION['email_ids'];
                unset($_SESSION['email_ids']);
                ?>
                <label>To <span class="star_red"></span></label>
                <span class="rltv"><?php echo $this->Form->textarea('Job.email', array('maxlength' => '300', 'class' => "required", 'placeholder' => 'Email', 'value' => $emails, 'readonly' => true)) ?>
                </span>
            </div>
            <div class="form_lst">
                <label>Subject <span class="star_red">*</span></label>
                <span class="rltv"><?php echo $this->Form->text('Job.subject', array('maxlength' => '255', 'label' => '', 'div' => false, 'class' => "required", 'placeholder' => 'Subject')) ?></span>
            </div>
            <div class="form_lst">
                <label>Message <span class="star_red">*</span></label>
                <span class="rltv"><?php echo $this->Form->textarea('Job.message', array('class' => "required", 'placeholder' => 'Message')) ?>
                </span>
            </div>
            <div class="form_lst sssss">
                <label class="removeColn">&nbsp;</label>
                <span class="rltv">
                    <div class="pro_row_left">
                        <?php echo $this->Form->submit('Send', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                        <?php echo $this->Html->link('Cancel', array('controller' => 'jobs', 'action' => 'accdetail', $jobInfo['Job']['slug']), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
                    </div> 
                </span>
            </div>
            <?php echo $this->Form->end(); ?> 
        </div>        
    </div>
</div>
<?php exit; ?>