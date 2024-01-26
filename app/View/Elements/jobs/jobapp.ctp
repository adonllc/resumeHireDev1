<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var JobSlug = $('#JobSlug').val();
        jQuery("#nullApplypopForm").validate({
            submitHandler: function (form) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo HTTP_PATH; ?>/jobs/<?php echo $actionc ?>/" + JobSlug,
                    data: $("#nullApplypopForm").serialize(),
                    dataType: "text",
                    beforeSend: function () {

                        $('#logbtn').attr('disabled', 'disabled');
                        $('#logbtn').val('<?php echo __d('user', 'Please wait...', true) ?>');
                    },
                    success: function (result) {

                        $('#logbtn').removeAttr('disabled', 'disabled');
                       $('#logbtn').val('<?php echo __d('user', 'Submit', true) ?>');
                        try {
                            var obj = JSON.parse(result);
                            JSON.parse(result);
                        } catch (e) {
                            $('#innercd').html(result);
                        }
                        if (obj.success == 0) {
                            alert(obj.message);
                        } else {
                            var JobId = $('#JobId').val();
                            $('#applybtn' + JobId).attr('onclick', "");
                            $('#applybtn' + JobId).html('<?php echo __d('user', 'Already Applied', true) ?>');
                            $('#applypop').removeClass("popupc");
                            $('#applypop').html("");
                            alert(obj.message);
                        }

                    }
                });
            }
        });
    });

</script> 
<?php echo $this->Form->create('null', array('url' => array('controller' => 'jobs', 'action' => $actionc, 'slug' => $job['Job']['slug']), 'enctype' => 'multipart/form-data', "method" => "Post")); ?>

<div class="nzwh-wrapper">

    <fieldset class="nzwh">

        <legend class="nzwh"><h2> <?php echo __d('user', 'Job Application Confirmation', true); ?>  </h2></legend>
        <div class="declaration_title">
            <span><?php echo __d('user', 'Declaration', true); ?> :</span> 
        </div>
        <div class="declaration_descrption">
            <span><?php echo __d('user', 'The information in this application form is true and complete. I agree that any deliberate omission, falsification or misrepresentation in the application form will be grounds for rejecting this application or subsequent dismissal if employed by the organisation. Where applicable, I consent that the organisation can seek clarification regarding professional registration details.', true); ?></span> 
        </div>
        <div class="declaration_check">           
            <?php echo $this->Form->checkbox('JobApply.declare', array('class' => "required", 'value' => "1",'id'=>'declaration')); ?><div class="star_red">*</div> <label for="declaration"><?php echo __d('user', 'I agree to the above declaration.', true); ?></label>
        </div>
        <?php
        $optionNotification = classregistry::init('CoverLetter')->find('list', array('conditions' => array('CoverLetter.user_id' => $this->Session->read('user_id')), 'fields' => array('CoverLetter.id', 'CoverLetter.title')));
        ?>
        <div class="drt">
            <span class="fdsf"> <?php echo __d('user', 'Please Select the Cover Letter', true); ?>.</span>
        </div>
        <div class="drt">
            <div class="radio-inline">
                <?php
                if (!empty($optionNotification)) {

                    $default = min(array_keys($optionNotification));
                    $attributes = array('default' => $default, 'legend' => false, 'hiddenField' => false, 'label' => false, 'class' => 'radiobtn', 'separator' => '</div><div class="radio-inline">');
                    echo $this->Form->radio('JobApply.cover_letter', $optionNotification, $attributes);
                } else {
                    echo __d('user', 'Please add a cover letter or apply without cover letter.', true);
                }
                ?>
            </div>
        </div>
        <div class="clear"></div>

        <div class="clear"></div>
        <?php echo $this->Form->hidden('Job.slug', array('value' => $job['Job']['slug'])); ?>
        <?php echo $this->Form->hidden('Job.id', array('value' => $job['Job']['id'])); ?>
        <?php
        if (empty($optionNotification)) {
            echo $this->Form->hidden('Job.cover_letter', array('value' => 0));
        }
        ?>


        <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'id' => 'logbtn')); ?>
        <?php echo $this->Html->link(__d('user', 'Add Cover Letter', true), array('controller' => 'candidates', 'action' => 'editProfile/' . 'return'), array('class' => 'input_btn rigjt add_cover_letter_bt', 'escape' => false, 'rel' => 'nofollow')); ?>

    </fieldset>

</div>
<?php echo $this->Form->end(); ?> 
<div class="vv" onclick="closepopJob()"></div>