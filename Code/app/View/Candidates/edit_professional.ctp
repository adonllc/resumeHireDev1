<script>
    $(document).ready(function () {
        $("#editExperience").validate();
    });
</script>
<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<?php echo $this->Html->script('jquery.validate.js'); ?>

<script>

    $(document).ready(function () {

        $("#addexperience").click(function () {
            var cc = ($('#experiencecounter').val() * 1) + 1;
            $('#experiencecounter').val(cc);
            $("#loader1").show();
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/candidates/openprofessional/" + cc,
                cache: false,
                success: function (responseText) {
                    $("#loader1").hide();
                    $('#experienceElement').append(responseText);
                    $("#loader1").hide();
                }
            });
        });

    });

    function removeCC(cc, id) {
        if (id) {
            $("#loader1").show();
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/candidates/deleteprofessional/" + id,
                cache: false,
                success: function (responseText) {
                    $('#dynamic' + cc).remove();
                    $("#loader1").hide();

                    window.location.href = "<?php echo HTTP_PATH; ?>/candidates/editProfessional";
                }
            });
        } else {
            $('#dynamic' + cc).remove();
        }
    }


</script>


<?php
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');

?>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                  <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                <div class="rght_dv">

                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-skills-boxes"><h2><i><?php echo $this->Html->image('front/home/professional-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Professional Registration', true); ?></span></h2></div>
                        <div class="information_cntn" style="position:inherit !important;">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editExperience')); ?>


                            <div id="experienceElement"  class="experienceElement">

                                <?php
                                if (isset($proDetails) && !empty($proDetails)) {
                                    $count = 0;
                                    foreach ($proDetails as $proDetail) {
                                        ?>
                                <div class="colify-title"><?php echo __d('user', 'Professional Registration', true); ?> <?php echo $count + 1; ?></div>

                                        <div id="dynamic<?php echo $count; ?>" class="dynamiccc">
                                              <?php echo $this->Form->hidden('Professional.' . $count . '.id'); ?>
                                            
                                              <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Name of Professional Governing Body', true); ?> <span class="star_red">*</span></label>
                                                <div class="form_input_education">
                                                    <?php echo $this->Form->text('Professional.' . $count . '.registration', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Professional Registration', true))) ?>
                                                </div>
                                            </div>
                                            
                                            <?php echo $this->Form->hidden('Professional.' . $count . '.id'); ?>
                                            <div class="wewa">
                                                <div class="wewain">
                                                    <?php echo $this->Html->link('<i class="fa fa-trash"></i>'.__d('user', 'Remove', true), 'javascript:removeCC("' . $count . '","' . $proDetail['Professional']['id'] . '");', array('confirm' => __d('user', 'Are you sure you want to delete this row ?', true), 'escape' => false, 'rel' => 'nofollow')); ?>
                                                </div>
                                            </div>


                                        </div>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                <?php } else { ?>

                                    <div id="dynamic0" class="dynamiccc">
                                        
                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Name of Professional Governing Body', true); ?> <span class="star_red">*</span></label>
                                            <div class="form_input_education">
                                                <?php echo $this->Form->text('Professional.0.registration', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Name of Professional Governing Body', true))) ?>
                                            </div>
                                        </div>
                                    
                                    </div>    
                                <?php } ?>



                                <div class="clear"></div><br/>

                            </div>
                            <div class="experienceElement">
                                <div class="wewain-add">
                                    <div id="loader1" class="loader" style="display:none; left: 50%;   position:absolute"><?php echo $this->Html->image("loading.gif"); ?></div>
                                    <?php echo $this->Html->link(__d('user', '+ Add More', true), 'javascript:void(0);', array('id' => 'addexperience', 'class' => 'add_btn', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>
                            </div>




                            <div class="form_lst sssss">
                                <label class="blank_label">&nbsp;</label>
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php //echo $this->Form->hidden('User.old_cv');   ?>
                                        <?php
                                        if (isset($proDetails) && !empty($proDetails)) {
                                            echo $this->Form->input('Candidate.cc', array('type' => 'hidden', 'id' => 'experiencecounter', 'value' => (count($proDetails) - 1)));
                                        } else {
                                            echo $this->Form->input('Candidate.cc', array('type' => 'hidden', 'id' => 'experiencecounter', 'value' => '0'));
                                        }
                                        ?>
                                        <?php echo $this->Form->submit(__d('user', 'Update', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                                        <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'users', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
                                    </div> 
                                </span>
                            </div>
                            <?php echo $this->Form->end(); ?> 
                        </div>        
                    </div>
                </div> 
                  </div>
            </div>
        </div>
    </div>
</div>

