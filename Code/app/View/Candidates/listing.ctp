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
<script>
    function updateCity(stateId) {
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/User/" + stateId,
            cache: false,
            success: function (result) {
                $("#updateCity").html(result);
                $('#loaderID').hide();
            }
        });
    }
</script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>
<div class="inner_page_top_bg_null"></div>
<div class="clear"></div>
<div class="iner_pages_formate_box">
    <div class="wrapper">
        <div class="iner_form_bg_box">
            <div class="top_page_name_box">
                <div class="page_name_boox"><span><?php echo __d('user', 'Jobseekers', true);?></span></div>
                <!--                <div class="top_bt_action">
                                    <ul>
                                        <li><a href="#" class="active">Featured Jobs</a></li>
                                        <li><a href="#">All Jobs</a></li>
                                    </ul>
                                </div>-->
            </div>
            <div class="clear"></div>


            <div class="row_hr">
                <div class="cols_3">
                    <div class="job_searc_white_box job_searc_white_box_no">
                        <div class="job_search_filter_title"><?php echo __d('user', 'Jobseeker Search', true);?></div>
                        <div class="clear"></div>

                        <?php echo $this->Form->create("Candidate", array('url' => 'listing', 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob', 'name' => 'searchJob')); ?>                        
                        <div class="left_sec_form_1">
                            <div class="div_full_dfaya">
                                <div class="data_row_fulk">
                                    <div class="full_cols">
                                        <div class="key_word_txet"><?php echo __d('user', 'Keywords', true);?></div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->text('User.keyword', array('placeholder' =>  __d('user', 'Enter Keyword(s)', true), 'label' => '', 'div' => false, 'class' => "search_input")) ?>
                                    </div>

                                    <!-- <div class="full_cols">
                                            <div class="key_word_txet">State</div>
                                            <div class="clear"></div>
                                    <?php //echo $this->Form->input('User.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any State', 'onChange' => 'updateCity(this.value)')); ?>
                                        </div>
                                        <div class="full_cols">
                                            <div class="key_word_txet">City</div>
                                            <div class="clear"></div>
                                            <div id="updateCity">
                                    <?php //echo $this->Form->input('User.city_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any City')); ?>
                                            </div>
                                        </div> -->

                                    <div class="full_cols">
                                        <div class="key_word_txet"><?php echo __d('user', 'Location', true);?></div>
                                        <div class="clear"></div>
                                        <?php 
                                            //echo $this->Form->input('User.location', array('type' => 'select', 'options' => $locationlList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any location'));
                                            echo $this->Form->text('User.location', array('placeholder' =>  __d('user', 'Enter Location', true), 'label' => '', 'div' => false, 'class' => "search_input"))
                                        ?>  
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet"><?php echo __d('user', 'Skills', true);?></div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->select('User.skills', $skillList, array('multiple' => true, 'data-placeholder' =>  __d('user', 'Choose skills', true), 'class' => "chosen-select required")); ?>
                                        <?php //echo $this->Form->input('User.skills', array('type' => 'select', 'options' => $skillList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Skills')); ?>
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet"><?php echo __d('user', 'Experience', true);?></div>
                                        <div class="clear"></div>
                                        <?php 
                                        global $experienceArray;
                                        echo $this->Form->select('User.total_exp', $experienceArray, array('empty' =>  __d('user', 'Select Experience', true),'class' => "search_input")); ?>
                                    </div>
                                    
                                    <div class="full_cols">
                                        <div class="key_word_txet"><?php echo __d('user', 'Expected Salary', true);?></div>
                                        <div class="clear"></div>
                                        <?php 
                                        global $sallery;
                                        echo $this->Form->select('User.exp_salary', $sallery, array('empty' =>  __d('user', 'Select  Expected Salary', true),'class' => "search_input"));
                                        ?>
                                    </div>
                                    
                                    <?php /*<div class="full_cols">
                                        <div class="key_word_txet">By Course</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->select('User.basic_course_id', $basicCourseList, array('empty' => 'Select Course','class' => "search_input")); ?>
                                        <?php echo $this->Ajax->observefield('UserBasicCourseId', array('url' => '/candidates/specilylistsearch', 'update' => 'specilyListBasic')); ?>
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet">By Specialization</div>
                                        <div class="clear"></div>
                                        <div class="searchl" id="specilyListBasic">
                                            <?php echo $this->Form->select('User.basic_specialization_id', $basicspecializationList, array('empty' => 'Select Specialization','class' => "search_input")); ?>
                                        </div>
                                    </div> */?>
                                    
                            
                                    
                                    
                                </div>
                            </div>
                            <!--<div class="div_full_dfaya">
                                <div class="data_row_fulk">
                                    <div class="full_cols">
                                        <div class="key_word_txet">CTC</div>
                                        <div class="clear"></div>
                            <?php
                            //global $salary;
                            //echo $this->Form->input('User.salary_from', array('type' => 'select', 'options' => $salary, 'label' => false, 'div' => false, 'class' => "search_input search_input_small", 'empty' => 'Select'));
                            ?>
                                        <span class="to_text">to</span>
                            <?php //echo $this->Form->input('User.salary_to', array('type' => 'select', 'options' => $salary, 'label' => false, 'div' => false, 'class' => "search_input search_input_small", 'empty' => 'Select')); ?>                                   
                                    </div>
                                   
                                    
                                </div>
                            </div>-->
                        </div>	
                        <div class="full_cols full_cols_btt full_colsdr">
                            <?php echo $this->Ajax->submit( __d('user', 'Search', true), array('div' => false, 'url' => array('controller' => 'candidates', 'action' => 'listing'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => '')); ?>
                            <?php echo $this->Html->link( __d('user', 'Reset', true), array('controller' => 'candidates', 'action' => 'listing'), array('class' => '','rel'=>'nofollow')); ?>
                        </div>
                        <?php $this->Form->end(); ?>
                    </div>
                </div>

                <div class="cols_7">
                    <div id="listID" style="overflow: auto;">
                        <?php echo $this->element('candidates/listing'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
