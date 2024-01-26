<?php
echo $this->Html->script('jquery.validate.js');
?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<?php echo $this->Html->css('front/sample.css'); ?>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION;?>"></script> 
<script>
    var autocomplete;
//    var options = {
////            types: ['(cities)'],
//fields:["address_component", "adr_address", "formatted_address", "geometry",  "name", "place_id"],
//   componentRestrictions: {country: ['US','CA']}
//        }
    function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('job_city')));
    }
    window.onload = initialize;
</script>
<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<?php echo $this->Html->css('front/sample.css'); ?>
<script>
    $(document).ready(function () {
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9-]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true);?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true);?>.");
        $.validator.addMethod("my_url", function (value, element) {
            var regexp = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/
            return this.optional(element) || regexp.test(value);
        }, "<?php echo __d('user', 'Please enter a valid URL', true);?>");
        $("#editProfile").validate();
    });



</script>

<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="container">
            <div class="row">
                <div class="my_acc">
                    <?php echo $this->element('left_menu'); ?>
                    <div class="col-lg-9">

                        <div class="my-profile-boxes">
                            <div class="my-profile-boxes-top my-experience-boxes"><h2><i><?php echo $this->Html->image('front/home/editprofile-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Edit Profile Information', true);?></span></h2></div>
                            <div class="information_cntn">
                                <?php echo $this->element('session_msg'); ?>

                                <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editProfile', 'class' => "form_trl_box_show2", 'name' => 'changeprofilepicture')); ?>

                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Company Name', true);?> <span class="star_red">*</span></label>
                                   <div class="form_input_education">
                                        <?php echo $this->Form->text('User.company_name', array('maxlength' => '255', 'label' => '', 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Company Name', true))) ?>
                                   </div>
                                </div>

                                <div class="form_list_education">
                                    <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Company Profile', true);?> <span class="star_red">*</span></span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->textarea('User.company_about', array('class' => "form-control required", 'placeholder' => __d('user', 'Company Profile', true))) ?>
                                    </div>
                                </div>

                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Position', true);?> <span class="star_red">*</span></label>
                                   <div class="form_input_education">
                                        <?php echo $this->Form->text('User.position', array('maxlength' => '255', 'label' => '', 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Position', true))) ?>
                                   </div>
                                </div>
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'First Name', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->text('User.first_name', array('maxlength' => '20', 'label' => '', 'div' => false, 'class' => "form-control required validname", 'placeholder' => __d('user', 'First Name', true))) ?>
                                    </div>
                                </div>
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Last Name', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->text('User.last_name', array('maxlength' => '20', 'label' => '', 'div' => false, 'class' => "form-control required validname", 'placeholder' => __d('user', 'Last Name', true))) ?>
                                    </div>
                                </div>
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Address', true);?> <span class="star_red">*</span></label>
                                <div class="form_input_education">
                                        <?php echo $this->Form->textarea('User.address', array('class' => "form-control required", 'placeholder' => __d('user', 'Address', true))) ?>
                                </div>
                                </div>
                                
                                
                                <div class="form_list_education">
                                   <label class="lable-acc"><?php echo __d('user', 'Location', true);?> <div class="star_red">*</div></label>
                                   <div class="form_input_education">
                                        <?php // echo $this->Form->select('User.location', $locationlList, array('data-placeholder' => __d('user', 'Choose location', true), 'class' => "chosen-select required")); ?>
                                        <?php echo $this->Form->text('User.location', array('id' => 'job_city', 'class' => "form-control required", 'placeholder' => __d('user', 'Location', true))) ?>
                                    </div>
                                </div>

                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Contact Number', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->text('User.contact', array('maxlength' => '16','minlength' => '8', 'class' => "form-control contact required", 'placeholder' => __d('user', 'Contact Number', true))) ?>
                                    </div>
                                </div>
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Company Number', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->text('User.company_contact', array('maxlength' => '16', 'minlength' => '8','class' => "form-control contact required", 'placeholder' => __d('user', 'Company Number', true))) ?>
                                    </div>
                                </div>
                                <div class="form_list_education">
                                   <label class="lable-acc"><?php echo __d('user', 'Company Website', true);?> </label>
                                    <div class="form_input_education"><?php echo $this->Form->text('User.url', array('class' => "form-control url", 'placeholder' => __d('user', 'Company Website', true))) ?>
                                        Eg.: http://www.google.com or http://google.com
                                    </div>
                                </div>
                                <div class="form_list_education">
                                   <label class="lable-acc"><?php echo __d('user', 'Upload Establishment photo', true); ?></label>
                                    <div class="form_input_education form_upload_file">
                                        <span class="choose-file-your">Choose File</span>
                                        <?php echo $this->Form->file('User.company_logo', array('class' => 'form-control')); ?>
                                        

                                        <?php
                                        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $UseroldImage['User']['company_logo'];
                                        if (file_exists($path) && !empty($UseroldImage['User']['company_logo'])) {
                                            ?>


                                            <?php
                                            echo '<b>' . $UseroldImage['User']['company_logo'] . '</b>';
                                        }
                                        ?>                                      

                                    </div>
                                   <div class="abccc pstrength-minchar"><?php echo __d('user', 'Supported File Types', true); ?>: jpg, jpeg, png.</div>
                                </div>
                                <!--                            <div class="form_lst">
                                                                <label>Industry in which you work <span class="star_red">*</span></label>
                                                                <span class="rltv">
                                <?php //echo $this->Form->textarea('User.industry', array('class' => "required", 'placeholder' => 'Industry in which you work')) ?>
                                                                </span>
                                                                <span class="rltv"><?php //echo $this->Form->input('User.industry', array('type' => 'select', 'options' => $industryList, 'label' => false, 'div' => false, 'class' => "required", 'empty' => 'Select Industry'))   ?></span>
                                                            </div>-->
                                <!--                            <div class="form_lst">
                                                                <label>Description <span class="star_red">*</span></label>
                                                                <span class="rltv"><?php //echo $this->Form->textarea('User.description', array('maxlength' => '300', 'class' => "required", 'placeholder' => 'Description'))   ?>
                                                                </span>
                                                            </div>-->
                                <div class="form_lst sssss">
                                    <span class="rltv">
                                        <div class="pro_row_left">
                                            <?php //echo $this->Form->hidden('User.old_abn'); ?>  
                                             <?php echo $this->Form->hidden('User.old_image'); ?>   
                                            <?php echo $this->Form->submit(__d('user', 'Update', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                                            <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'users', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
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
<script>
    CKEDITOR.replace('data[User][company_about]', {
        toolbar:
                [
                    {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline']},
                    {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-']},
                    {name: 'links', items: ['Link', 'Unlink']},
                    {name: 'tools', items: ['']}
                ],
        language: '',
        height: 150,
        width: 563
                //uiColor: '#884EA1'
    });
</script>