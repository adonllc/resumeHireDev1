
<?php
echo $this->Html->script('facebox.js');
echo $this->Html->css('facebox.css');
?>

<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })

    $(document).ready(function () {
        var catSlug = $("#catSlug").val();
        $("#JobCreated").change(function () {
            $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/jobs/jobListing/" + catSlug,
                cache: false,
                data: $('#searchJob2').serialize(),
                beforeSend: function () {
                    $("#loaderID").show();
                },
                complete: function () {
                    $("#loaderID").hide();
                },
                success: function (result) {
                    $("#loaderID").hide();
                    if (result) {
                        $("#listID").html(result);

                    }

                }
            });

//   alert($('#searchJob2').serialize());
        });

    });

</script>

<?php if ($jobs) { ?>
    <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js"></script>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:191px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'jobListing', $slug, $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID', 'complete' => '$("html, body").animate({ scrollTop: 200 }, "slow");'));
    ?>

    <div class="inputsortlisting_sec">
        <?php
        $option = array(
            'created ASC' => 'Date ASC',
            'created DESC' => 'Date Desc',
            'title ASC' => 'Title ASC',
            'title DESC' => 'Title Desc',
        );
        echo $this->Form->input('Job.created', array('type' => 'select', 'options' => $option, 'label' => false, 'class' => "search_input_small", 'empty' => 'Sort By'));
        echo $this->Form->input('Job.slug', array('id' => 'catSlug', 'type' => 'hidden', 'value' => $slug, 'label' => false));
        ?>

    </div>

    <?php echo $this->Form->end(); ?>
    <!---This form is end for filters in Element->jobs->job_listing.ctp--->

    <!----starts section of founded listed jobs by main search or filter search---->
    <div class="paging" style="width:100%;">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>No. of Records </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> of </span><span class="badge-gray">{:count}</span>'
            );
            ?> 
        </div>

        <div class="paginations">

            <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));  ?> 
            <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow')); ?> 
        </div>
        <br>
        <br>	
    </div>
    <div class="listing_box_full listing_box_full_fulll">
        <?php echo $this->element('session_msg'); ?>

        <?php
        $count = 1;
        foreach ($jobs as $job) {
            ClassRegistry::init('Job')->updateJobSearch($job['Job']['id']);

            if ($job['Job']['type'] == 'gold') {
                $class = 'listing_full_row_bg';
            } else {
                $class = '';
            }
            ?>
            <!--right filter Section starts-->
            <div class="listing_full_row <?php echo $class; ?> ">
                <div class="listing_col_left">
                    <div class="lisint_box_title">
                        <?php //echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html'), array('target' => '_blank')); ?>
                        <?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html')); ?>

                        <div class="listing_boxbfg">
                            <div class="open_bt">
                                <?php //echo $this->Html->link('Details', array('controller' => 'jobs', 'action' => 'detail','cat'=>$job['Category']['slug'], 'slug'=>$job['Job']['slug'],'ext'=>'html'), array('class' => 'sstar', 'target' => '_blank')); ?>
                                <?php echo $this->Html->link(__d('user', 'Details', true), array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html'), array('class' => 'sstar')); ?>

                                <?php
                                if ($this->Session->read('user_type') != 'recruiter') {

                                    if ($this->Session->read('user_id')) {
                                        $apply_status = classregistry::init('JobApply')->find('first', array('conditions' => array('JobApply.user_id' => $this->Session->read('user_id'), 'JobApply.job_id' => $job['Job']['id'])));
                                        if (empty($apply_status)) {
                                            //echo $this->Html->link('Apply Now', 'javascript:void(0);', array('class' => 'sstar', 'onclick' => 'jobApplyConfitm();'));
                                            echo '<a href="#confirmPopup' . $job["Job"]["id"] . '" rel="facebox" class = "sstar">'.__d('user', 'Apply Now', true).'</a>';
                                        } else {
                                            echo $this->Html->link(__d('user', 'Already Applied', true), 'javascript:void(0);', array('class' => 'sstar'));
                                        }
                                    } else {

                                        echo $this->Html->link(__d('user', 'Apply Now', true), array('controller' => 'jobs', 'action' => 'jobApply', $job['Job']['slug']), array('class' => 'sstar'));
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div> 

                    <div class="company_div"><?php echo $job['Job']['company_name'] ? $job['Job']['company_name'] : 'N/A'; ?></div>
                    <div class="list_location_box">
                        <span class="listing_loc_exp"> <b> <i class="fa fa-briefcase" aria-hidden="true"></i></b>  <!--<?php //echo substr($job['City']['city_name'] . ', ' . $job['State']['state_name'] . ', ' . $job['Job']['postal_code'], 0, 40);                                       ?>--> 
                            &nbsp; <?php echo $job['Job']['min_exp'] . ' - ' . $job['Job']['max_exp'] . ' '.__d('user', 'yrs', true); ?></span>
                        <span class="listing_loc_exp"> <b> <i class="fa fa-map-marker" aria-hidden="true"></i></b>  <!--<?php //echo substr($job['City']['city_name'] . ', ' . $job['State']['state_name'] . ', ' . $job['Job']['postal_code'], 0, 40);                                       ?>--> 
                            &nbsp; <?php echo $job['Job']['job_city'] ? $job['Job']['job_city'] : 'N/A'; ?></span>

                    </div>  
                    <div class="data_row_ful_skil_content2">

                        <div class="big_desc"><span class="first_span">Keyskills :</span>
                            <span class="second_span"><?php
                                $jobskill = ClassRegistry::init('Job')->field('skill', array('Job.id' => $job['Job']['id']));
                                $jobId = explode(',', $jobskill);
                                $i = 1;
                                foreach ($jobId as $id) {
                                    $skill = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $id));

                                    if (!empty($skill)) {
                                        if ($i == 1) {
                                            echo $skill;
                                        } else {
                                            echo " , " . $skill;
                                        }
                                        $i = $i + 1;
                                    } else {
                                        echo"N/A";
                                    }
                                }
                                ?>
                            </span>

                        </div>

                        <div class="big_desc"><span class="first_span">Designation :</span>
                            <span class="second_span">
                                <?php
                                $jobDesignation = ClassRegistry::init('Job')->field('designation', array('Job.id' => $job['Job']['id']));
                                // pr($jobDesignation);
                                $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $jobDesignation, 'Skill.type' => 'Designation'));
                                if (!empty($designation)) {
                                    echo $designation;
                                } else {
                                    echo 'N/A';
                                }
                                ?>

                            </span>

                        </div>



                        <?php
                        if (!empty($job['Job']['description'])) {
                            if (str_word_count($job['Job']['description']) > 10) {
                                ?>
                                <div class="big_desc">
                                    <span class="first_span">Job Description :</span>
                                    <span class="second_span" style="text-align: justify;">
                                        <?php
                                        //echo $this->Text->truncate($job['Job']['description'], 150, array('html' => true));

                                        if (str_word_count($job['Job']['description']) > 10 && str_word_count($job['Job']['description']) < 50) {
                                            $pos = strpos($job['Job']['description'], ' ', 50);
                                            echo substr($job['Job']['description'], 0, $pos) . '...';
                                        } elseif (str_word_count($job['Job']['description']) > 51 && str_word_count($job['Job']['description']) < 100) {
                                            $pos = strpos($job['Job']['description'], ' ', 100);
                                            echo substr($job['Job']['description'], 0, $pos) . '...';
                                        } elseif (str_word_count($job['Job']['description']) > 101 && str_word_count($job['Job']['description']) < 120) {
                                            $pos = strpos($job['Job']['description'], ' ', 100);
                                            echo substr($job['Job']['description'], 0, $pos) . '...';
                                        } elseif (str_word_count($job['Job']['description']) > 121 && str_word_count($job['Job']['description']) < 150) {
                                            $pos = strpos($job['Job']['description'], ' ', 120);
                                            echo substr($job['Job']['description'], 0, $pos) . '...';
                                        } else {
                                            $pos = strpos($job['Job']['description'], ' ', 150);
                                            echo substr($job['Job']['description'], 0, $pos) . '...';
                                        }
                                        ?>
                                    </span>  
                                    <?php
                                    /*    $industry = ClassRegistry::init('Industry')->field('name', array('Industry.id' => $job['User']['industry']));
                                      if ($industry) {
                                      echo $industry;
                                      } else {
                                      echo 'N/A';
                                      } */
                                    ?>

                                </div>
                                <?php
                            }
                        }
                        ?>

                    </div>  



                    <div class="list_bot_boox list_socials">
                        <div class="list_bot_boox_table">
                            <div class="list_bot_boox_row">
                                <div class="list_bot_boox_col">

                                    <?php echo $this->Html->image('front/dolor_icn.png', array('alt' => 'icon')); ?>
                                    <span><?php echo CURRENCY . intval($job['Job']['min_salary']) . 'K - ' . CURRENCY . intval($job['Job']['max_salary']) . 'K'; ?></span>
                                </div>

                                <div class="list_bot_boox_col">
                                    <?php echo $this->Html->image("front/company_icon.png", array("alt" => "icon",)); ?> 
                                    <span>
                                        <?php
                                        global $worktype;
                                        echo $job['Job']['work_type'] ? $worktype[$job['Job']['work_type']] : 'N/A';
                                        ?>
                                    </span>
                                </div>

                                <div class="list_bot_boox_col">
                                    <?php echo $this->Html->image('front/calander_icon.png', array('alt' => 'icon')); ?><span><?php echo date('F j, Y', strtotime($job['Job']['created'])); //date('jS F,Y h:i A'           ?></span>
                                </div>
                                <div class="list_bot_boox_col">
                                    <div class="savejob">
                                        <?php
                                        if ($this->Session->read('user_type') != 'recruiter') {
                                            $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $job['Job']['id'])));
                                            if (empty($short_status)) {
                                                //echo $this->Html->link(' Save Job', array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false,'rel'=>'nofollow'));

                                                echo $this->Html->image("front/savejob.png", array(
                                                    "alt" => "icon",
                                                    'url' => array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug'])
                                                ));
                                                echo' <span>' . $this->Html->link(' '.__d('user', 'Save Job', true), array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow')) . '</span>';
                                            } else {
                                                //echo' <span>' . $this->Html->link(' Save Job', array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false,'rel'=>'nofollow')) .'</span>';
                                                echo $this->Html->image("front/savejob2.png", array(
                                                    "alt" => "icon",
                                                ));
                                                echo $this->Html->link(' '.__d('user', 'Already Saved', true), 'javascript:void(0);', array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow'));
                                            }
                                        }
                                        ?>
                                       <!-- <a href="#"><?php //echo $this->Html->image('front/savejob.png', array('alt' => 'icon'));                              ?><span>Save Job</span></a></div>-->

                                    </div>
                                </div>

                                <div class="list_bot_boox_col">
                                    <div class="addthis_button addthis_button_mar" addthis:url="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><i class="fa fa-share-alt"></i>
                                        Share to a friend</div>
                                </div>
                            </div>
                        </div>
                    </div>                     
                </div>
                <div class="listing_col_right">
                    <div class="open_deta_bt">

                        <div class="open_bt detail_bt detail_bt_nee">

                        </div>
                    </div>
                </div>

            </div>
            <!--right filter Section ends-->



            <!-------------------popup box start------------>
            <?php if ($this->Session->read('user_id') != '') { ?>

                <div id="confirmPopup<?php echo $job["Job"]["id"] ?>" style="display: none;">
                    <!-- Fieldset -->

                    <?php echo $this->Form->create('Job', array('url' => array('url' => 'jobApply/' . $job['Job']['slug']), 'enctype' => 'multipart/form-data', "method" => "Post")); ?>

                    <div class="nzwh-wrapper">

                        <fieldset class="nzwh">

                            <legend class="nzwh"><h2> <?php echo __d('user', 'Job Application Confirmation', true);?>  </h2></legend>

                            <?php
                            $optionNotification = classregistry::init('CoverLetter')->find('list', array('conditions' => array('CoverLetter.user_id' => $this->Session->read('user_id')), 'fields' => array('CoverLetter.id', 'CoverLetter.title')));
                            ?>
                            <div class="drt">
                                <span class="fdsf"> <?php echo __d('user', 'Please Select the Cover Letter', true);?>.</span>
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
                            <?php
                            if (empty($optionNotification)) {
                                echo $this->Form->hidden('Job.cover_letter', array('value' => 0));
                            }
                            ?>


                            <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                            <?php echo $this->Html->link(__d('user', 'Add Cover Letter', true), array('controller' => 'candidates', 'action' => 'editProfile/' . 'return'), array('class' => 'input_btn rigjt add_cover_letter_bt', 'escape' => false, 'rel' => 'nofollow')); ?>

                        </fieldset>

                    </div>
                    <?php echo $this->Form->end(); ?> 
                </div>

            <?php } ?>

            <!-----------------popup box end------------------------>


            <?php
            $count++;
        }
        ?>
    </div>
    <?php //echo $this->Form->end();     ?>

    <div class="paging pagingsrt"  style="width:100%;">
        <div class="paging" style="width:100%;">
            <div class="noofproduct">
                <?php
                echo $this->Paginator->counter(
                        '<span>No. of Records </span><span class="badge-gray"  style="background: #0282cc none repeat scroll 0 0;">{:start}</span><span> - </span><span class="badge-gray"  style="background: #0282cc none repeat scroll 0 0;">{:end}</span><span> of </span><span class="badge-gray"  style="background: #0282cc none repeat scroll 0 0;">{:count}</span>'
                );
                ?> 
            </div>

            <div class="paginations">
                <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));  ?> 
                <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow'));    ?> 

            </div>	
        </div>
    </div>
<?php } else { ?>
    <div class="listing_box_full">
        <div class="listing_full_row listing_full_row_bg">
            <div class="nomatching"> 
                <h1>There are no jobs matching for your search criteria.</h1>
                <h3>Please searched with other options.</h3>
            </div>
        </div>
    </div>
<?php } ?>



