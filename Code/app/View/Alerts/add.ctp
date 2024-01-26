<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION;?>"></script> 
<script>
    var autocomplete;
    function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('UserLocation')));
    }    
</script>
<script type="text/javascript">
    window.onload = function () {
        initialize();        
    };
</script> 
<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">

    $(document).ready(function () {

        /************************************************************/
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*Note: Special characters, number and spaces are not allowed.");

        $("#addAlert").validate();
        /************************************************************/

    });

</script>

<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>


<?php echo $this->Html->css('front/sample.css'); ?>


<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>



<script>
//    $(document).ready(function () {
//
//        $('input[type="submit"]').on('click', function (e) {
//
//            if ($('#AlertDesignation_chosen').find('span:nth-child(1)').text() == 'Choose Designation')
//            {
//                $('#AlertDesignation_chosen').addClass('error');
//                $('#AlertDesignation_chosen').addClass('required');
//                $('#showerror').addClass('adderror');
//                $('#showerror').text('This field is required.');
//                e.preventDefault();
//            }
//        });
//
//    });
//
//
//
//    $(document).ready(function () {
//
//        $('#locDiv').find("#AlertDesignation_chosen a span").on("click", function () {
//            $('#AlertDesignation_chosen').removeClass('error');
//            $('#showerror').empty();
//        });
//
//
//    });

</script>
<style>
    .adderror{ border: 0 solid #f3665c !important;
               color: #f3665c !important;
               font-weight: normal; }
    </style>

    <div class="my_accnt">
        <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-lg-9 col-sm-9">

                       <div class="my-profile-boxes">
                           <div class="my-profile-boxes-top"><h2><i><?php echo $this->Html->image('front/home/creat-job-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Add Alert', true);?></span></h2></div>
                        <div class="information_cntn" style="position:inherit !important;">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'addAlert', 'class' => "form_trl_box_show2", 'name' => 'changeprofilepicture')); ?>

                            <!-- <div class="form_lst">
                                <label>Location <div class="star_red">*</div></label>
                                <div id="locDiv" class="rltv">
                            <?php //echo $this->Form->select('Alert.location', $locationlList, array('data-placeholder' => 'Choose location', 'class' => "chosen-select required")); ?>
                                    <label id="showerror"></label>
                                </div>
                            </div>-->

                            <div class="form_list_education" id="div_subcat">
                                <label class="lable-acc relative_label"><?php echo __d('user', 'Location', true);?> <span class="star_red">*</span><!--<span class="subcat_help_text"></span>--></label>
                                <span class="form_input_education subbb" id="subcat">
                                    <?php echo $this->Form->text('Alert.location', array('div' => false, 'class' => "form-control required ", 'placeholder' => __d('user', 'Enter Location', true), 'id'=>'UserLocation'));?>
                                    <?php //echo $this->Form->input('Alert.location', array('type' => 'select', 'options' => $locationlList, 'label' => false, 'div' => false, 'class' => "form-control required alertLoc", 'multiple' => true)) ?>
                                   
                                </span>  
                            </div>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Designation', true);?> <div class="star_red">*</div></label>
                                <div id="locDiv" class="form_input_education">
                                    <?php echo $this->Form->select('Alert.designation', $designationlList, array('data-placeholder' => __d('user', 'Designation', true), 'class' => "chosen-select required")); ?>
                                    <label id="showerror"></label>
                                </div>
                            </div>

                            <div class="form_lst sssss">
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                                        <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'alerts', 'action' => 'index'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
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



