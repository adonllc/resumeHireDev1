
<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
          $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_ ]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");
        $("#managereport").validate({
            submitHandler: function (form) {
                $("#report_message").html('');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo HTTP_PATH; ?>/users/reportproblem',
                    data: $("#managereport").serialize(),
                    beforeSend: function () {
                       // $("#loaderID").show();
                    },
                    complete: function () {
                       // $("#loaderID").hide();
                    },
                    success: function (data) {

                        //console.log(data); 
                        $('#ref').show();
                        $('#report_message').html(data);
                        $('#managereport')[0].reset();
                    }
                });

            }
        });
        
    });
</script>
<?php echo $this->Form->create('feedback', array('class' => 'cssForm', 'name' => 'feedback', 'id' => 'managereport')); ?>
<?php $url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>
<?php echo $this->element('session_msg'); ?>
<div class="feedback_form_row_inputs">
    <?php
    if (!empty($url)) {
        echo $this->Form->hidden('User.current_url', array('value' => $url));
    }
    ?>
    <div class="feedback_form_row">
        <?php echo $this->Form->text('User.name', array('class' => "required validname feed_input_sect validname", 'placeholder' => __d('user', 'Name', true))) ?>
    </div>
    <div class="feedback_form_row">
        <?php echo $this->Form->text('User.email_address', array('class' => "required email feed_input_sect", 'placeholder' => __d('user', 'Email Address', true))) ?>
    </div>
    <div class="feedback_form_row">
        <?php echo $this->Form->text('User.contact', array('maxlength' => '16','minlength' => '8', 'class' => "number feed_input_sect", 'placeholder' => __d('user', 'Contact no.', true))) ?>
    </div>
    <div class="feedback_form_row">
        <?php echo $this->Form->textarea('User.message', array('class' => "feed_input_sect", 'placeholder' => __d('user', 'Your feedback', true))) ?>
    </div>
</div>
<div class="feedback_form_row_button">
<!--    <input type="submit" class = "input_btn login_btn btn btn-success" onclick="reportFeedback()" value="Submit">-->
    <input type="submit" class = "input_btn login_btn btn btn-success" value="<?php echo __d('user', 'Submit', true);?>">
</div>

<?php echo $this->Form->end(); ?>

<script type="text/javascript">

    /* function reportFeedback() {
     
     $.ajax({
     url: "<?php //echo HTTP_PATH;    ?>/users/reportproblem",
     data: $('#managereport').serialize(),
     type: "post",
     cache: false,
     beforeSend: function () {
     $("#loaderID").show();
     },
     complete: function () {
     $("#loaderID").hide();
     },
     success: function (data) {
     //console.log(data); 
     $('#ref').show();
     $('#report_message').html(data);
     $('#report')[0].reset();
     },
     // error: function (data) {
     //   alert('error');
     // }
     
     });
     return false;
     }*/

</script>