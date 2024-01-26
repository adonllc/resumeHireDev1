<script>
    function updateCity(stateId){
        $('#loaderID').show();
        $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/Job/"+stateId,
            cache: false,
            success: function(result) {
                $("#updateCity").html(result);
                $('#loaderID').hide();
            }
        });
    }
    
    function updateSubCat(catId){
    $('#loaderID').show();
        $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/"+catId,
            cache: false,
            success: function(result) {
                $("#subcat").html(result);
                $('#loaderID').hide();
            }
        });
    }
</script>
<div class=""></div>
<div class="clear"></div>
<div class="iner_pages_formate_box">
    <div class="wrapper">
        <div class="iner_form_bg_box">
            <div class="top_page_name_box">
                <div class="page_name_boox"><span>LATEST JOBS</span></div>
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
                        <div class="job_search_filter_title">Job Search</div>
                        <div class="clear"></div>

                        <?php echo $this->Form->create("Job", array('url' => 'listing', 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob')); ?>                        
                        <div class="left_sec_form_1">
                            <div class="div_full_dfaya">
                                <div class="data_row_fulk">
                                    <div class="full_cols">
                                        <div class="key_word_txet">Keywords</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->text('Job.keyword', array('placeholder' => 'Enter Keyword(s)', 'label' => '', 'div' => false, 'class' => "search_input")) ?>
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet">Category</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Classification', 'onChange' => 'updateSubCat(this.value)')); ?>    
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet">Sub-Category</div>
                                        <div class="clear"></div>
                                        <div id="subcat">
                                            <?php echo $this->Form->input('Job.subcategory_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Sub Category')); ?>    
                                        </div>
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet">State</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->input('Job.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any State', 'onChange' => 'updateCity(this.value)')); ?>
                                    </div>
                                    <div class="full_cols">
                                        <div class="key_word_txet">City</div>
                                        <div class="clear"></div>
                                        <div id="updateCity">
                                            <?php echo $this->Form->input('Job.city_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any City')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="div_full_dfaya">
                                <div class="data_row_fulk">
                                    <div class="full_cols">
                                        <div class="key_word_txet">Salary</div>
                                        <div class="clear"></div>
                                        <?php
                                        global $salary;
                                        echo $this->Form->input('Job.salary_from', array('type' => 'select', 'options' => $salary, 'label' => false, 'div' => false, 'class' => "search_input search_input_small", 'empty' => 'Select'));
                                        ?>
                                        <span class="to_text">to</span>
                                        <?php echo $this->Form->input('Job.salary_to', array('type' => 'select', 'options' => $salary, 'label' => false, 'div' => false, 'class' => "search_input search_input_small", 'empty' => 'Select')); ?>                                   
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class="full_cols full_cols_btt">
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'listing'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'find_tab_bt more_opt')); ?>
                            <?php echo $this->Html->link('Reset', array('controller' => 'jobs', 'action' => 'listing'), array('class' => 'find_tab_bt more_opt reset_bt')); ?>
                        </div>
                        <?php $this->Form->end(); ?>
                    </div>
                </div>

                <div class="cols_7">
                    <div id="listID">
                        <?php  echo $this->element('jobs/listing'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
