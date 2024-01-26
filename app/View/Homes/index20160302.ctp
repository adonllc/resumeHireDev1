
<script>
    function updateCity(stateId) {
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/Job/" + stateId,
            cache: false,
            success: function (result) {
                $("#updateCity").html(result);
                $('#loaderID').hide();
            }
        });
    }

    function updateSubCat(catId) {
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#subcat").html(result);
                $('#loaderID').hide();
            }
        });
    }

    function res_updateCity(stateId) {
        $('#res_loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/Job/" + stateId,
            cache: false,
            success: function (result) {
                $("#res_updateCity").html(result);
                $('#res_loaderID').hide();
            }
        });
    }

    function res_updateSubCat(catId) {
        $('#res_loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#res_subcat").html(result);
                $('#res_loaderID').hide();
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        // Handler for .ready() called.
        $('#find_job_div, #find_job_div1').click(function () {
            $('html, body').animate({
                scrollTop: $('#banner_sec').offset().top
            }, '20');
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        // Handler for .ready() called.
        $('#how_it_work_div').click(function () {
            $('html, body').animate({
                scrollTop: $('#howitworks').offset().top
            }, '20');
        });
    });
</script>
<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
$video_url = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'video_link'));
//pr($video_url); die;
?> 
<aside class="banner_sec" id="banner_sec">
    <div class="wrapper">
        <div class="search_top_inner_box">
            <div class="search_top_bbox">
                <div class="ser_top_rgiht_sec">
                    <span class="white"><?php echo $sloganText; ?></span>
                </div>
                <div class="clear"></div>
                <div class="normal_search_show_div">
                    <div class="job_searc_white_box">
                        <div class="job_titlw_name">Job Search</div>
                        <div class="clear"></div>

                        <?php echo $this->Form->create("Job", array('url' => 'listing', 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob1')); ?>
                        <div class="left_sec_form">
                            <div class="row">
                                <div class="data_row_fulk">
                                    <div class="cols_three">
                                        <div class="key_word_txet">Keywords</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->text('Job.keyword', array('placeholder' => 'Enter Keyword(s)', 'label' => '', 'div' => false, 'class' => "search_input")) ?>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">Category</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Classification', 'onChange' => 'updateSubCat(this.value)')); ?>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">State</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->input('Job.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any State', 'onChange' => 'updateCity(this.value)')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="data_row_fulk">
                                    <div class="cols_three">
                                        <div class="key_word_txet">Salary</div>
                                        <div class="clear"></div>
                                        <?php
                                        global $salary;
                                        echo $this->Form->input('Job.salary_from', array('type' => 'select', 'options' => $salary, 'label' => false, 'div' => false, 'class' => "search_input search_input_small", 'empty' => 'Select'));
                                        ?>
                                        <span class="to_text">to</span>
                                        <?php echo $this->Form->input('Job.salary_to', array('type' => 'select', 'options' => $salary, 'label' => false, 'div' => false, 'class' => "search_input search_input_small", 'empty' => 'Select')); ?>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">Sub-Category</div>
                                        <div class="clear"></div>
                                        <div id="subcat">
                                            <?php echo $this->Form->input('Job.subcategory_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Sub Category')); ?>
                                        </div>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">City</div>
                                        <div class="clear"></div>
                                        <div id="updateCity">
                                            <?php echo $this->Form->input('Job.city_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any City')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class="right_sec_form">
                            <div id="loaderID" style="display:none;position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                            <?php echo $this->Form->submit('Search', array('div' => false, 'label' => false, 'class' => 'find_tab_bt more_opt change_neww')); ?>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>

                <div class="responsive_search_show_div" style="display: none;">
                    <div class="job_searc_white_box">
                        <div class="job_titlw_name">Job Search</div>
                        <div class="clear"></div>


                        <?php echo $this->Form->create("Job", array('url' => 'listing', 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob', 'name' => 'searchJob')); ?>
                        <div class="left_sec_form">
                            <div class="row">
                                <div class="data_row_fulk">
                                    <div class="cols_three">
                                        <div class="key_word_txet">Keywords</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->text('Job.keyword', array('placeholder' => 'Enter Keyword(s)', 'label' => '', 'div' => false, 'class' => "search_input")) ?>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">Category</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Classification', 'onChange' => 'res_updateSubCat(this.value)')); ?>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">Sub-Category</div>
                                        <div class="clear"></div>
                                        <div id="res_subcat">
                                            <?php echo $this->Form->input('Job.subcategory_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Sub Category')); ?>
                                        </div>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">State</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->input('Job.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any State', 'onChange' => 'res_updateCity(this.value)')); ?>
                                    </div>
                                    <div class="cols_three">
                                        <div class="key_word_txet">City</div>
                                        <div class="clear"></div>
                                        <div id="res_updateCity">
                                            <?php echo $this->Form->input('Job.city_id', array('type' => 'select', 'options' => '', 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any City')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="data_row_fulk">
                                    <div class="cols_three">
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
                        <div class="right_sec_form">
                            <div id="res_loaderID" style="display:none;position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                            <?php echo $this->Form->submit('Search', array('div' => false, 'label' => false, 'class' => 'find_tab_bt more_opt change_neww')); ?>
                        </div>
                        <?php $this->Form->end(); ?>
                    </div>
                </div>



            </div>
        </div>
    </div>
</aside>
<aside class="video_sec" id="howitworks">
    <div class="hwo_does_ttie"><div class="wrapper">How does <?php echo $site_title; ?> Work ?</div></div>
    <div class="clear"></div>
    <div class="wrapper">
        <div class="hwo_does_ttie_arrow"></div>
        <div class="clear"></div>

        <div class="vide_sec_box">          
            <iframe width="560" height="315" src="<?php echo $video_url; ?>" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="clear"></div>

        <div class="data_center">
            <div class="bt_vid_se">
                <a id="find_job_div">Find A Job</a>
                <?php //echo $this->Html->link('Find A  Job', array('controller' => 'jobs', 'action' => 'listing'), array('class' => '')); ?>
                <?php echo $this->Html->link('Post a Job', array('controller' => 'jobs', 'action' => 'selectType'), array('class' => 'redu_link')); ?>
            </div>
        </div>
    </div>
</aside>
<aside class="why_sec">
    <div class="wrapper">
        <div class="why_text_title">why <?php echo $site_title; ?> ?</div>
        <div class="clear"></div>
        <div class="whay_tagline">It benefits both and assists in the growing movement of a flexible workforce.</div>
        <div class="clear"></div>
        <div class="why_bbox">
            <div class="two_cols border_rgiht_s">                
                <div class="title_touoo"><?php echo $site_title; ?> for <?php echo $site_title; ?></div>
                <div class="clear"></div>

                <div class="why_desc">
                    <ul>
                        <li class="line_one">See the jobs that are right for you.</li>
                        <li>Speak to employers that speak your language.</li>
                        <li class="line_one">Be the first to know about the latest jobs in YOUR field of expertise.</li>
                    </ul>
                </div>
            </div>

            <div class="two_cols">                
                <div class="title_touoo"><?php echo $site_title; ?> for employers</div>
                <div class="clear"></div>

                <div class="why_desc">
                    <ul>
                        <li class="line_one"> Be where the Jobseekers are.</li>
                        <li>Let your job stand out from the crowd.</li>
                        <li class="line_one">Fill your role, first time every time with the right Jobseeker.</li>

                    </ul>
                </div>

            </div>
            <div class="clear"></div>
            <div class="bot_boy"></div>
        </div>
    </div>
</aside>
<aside class="started_sec">
    <div class="wrapper">
        <div class="title_nam_star">let’s get started</div>
        <div class="clear"></div>
        <div class="data_center">
            <div class="bt_vid_se bt_vid_se_no">
                <a id="find_job_div1">Find A Job</a>

                <?php echo $this->Html->link('Post a Job', array('controller' => 'jobs', 'action' => 'selectType'), array('class' => 'find_job_div1')); ?>
            </div>
        </div>
    </div>
</aside>