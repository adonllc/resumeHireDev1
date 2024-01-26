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

        $("#dateS").change(function () {

            $('#JobCreated').val($('#dateS').val());

            $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/jobs",
                cache: false,
                data: $('#searchJob1').serialize(),
                beforeSend: function () {
                    $("#loaderID").show();
                },
                complete: function () {
                    $("#loaderID").hide();
                },
                success: function (result) {
                    $("#loaderID").hide();
                    if (result) {
                        $("#filterSection").html(result);

                    }

                }
            });

        });

    });

</script>

<?php
if ($jobs) {
    ?>
    <script>
        var script = 'https://s7.addthis.com/js/250/addthis_widget.js#domready=1';
        if (window.addthis) {
            window.addthis = null;
            window._adr = null;
            window._atc = null;
            window._atd = null;
            window._ate = null;
            window._atr = null;
            window._atw = null;
        }
        $.getScript(script);
    </script>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:191px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'listing', $separator));
    //pr($urlArray); exit;
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'filterSection', 'url' => $urlArray, 'indicator' => 'loaderID', 'complete' => '$("html, body").animate({ scrollTop: 200 }, "slow");'));
    ?>

    <?php //echo $this->Form->end(); ?>
    <!---This form is end for filters in Element->jobs->listing.ctp--->

    <!----starts section of founded listed jobs by main search or filter search---->


    <?php echo $this->element('session_msg'); ?>
    <div class="row">
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
            <div class="col-lg-6 col-md-12 <?php echo $class; ?> ">
                <div class="job-list-wrapper">
                    <div class="job-post-list mt-4">
                        <div class="single-job">
                            <div class="job-meta">
                                <div class="title">
                                    <h4><?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html')); ?></h4>

<div class="meta-info">
                                                <div class="job-experience"><label>Experience: </label><span><?php
                                                        if ($job['Job']['max_exp'] > 15) {
                                                            echo $job['Job']['min_exp'] . ' - ' . 'more than 15 years';
                                                        } else {
                                                            echo $job['Job']['min_exp'] . ' - ' . $job['Job']['max_exp'] . ' ' . __d('user', 'yrs', true);
                                                        }
                                                        ?></span></div>
                                                <div class="job-salary-package"><?php 
                                                if (isset($job['Job']['min_salary']) && isset($job['Job']['max_salary'])) {
                                                    echo CURRENCY . ' ' . intval($job['Job']['min_salary']) . " - " . CURRENCY . ' ' . intval($job['Job']['max_salary']);
                                                } else {
                                                    echo "N/A";
                                                }?> /year</div>


                                            </div>
                                </div> 
                                
                            </div>
                             <div class="timing ml-auto">
                                 
                                <a class="time-btn-new" href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"> <?php
                                    global $worktype;
                                    echo $job['Job']['work_type'] ? $worktype[$job['Job']['work_type']] : 'N/A';
                                    ?></a>
                                 <div class="addthis_button addthis_button_share" addthis:url="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>">
                                       <?php echo $this->Html->image('front/home/share-icon.png', array('alt' => '')); ?>
                                    </div>
                                
                            </div>
                            <div class="client-logo-img">
                            <div class="logo-client-site">
                                <a href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php // echo $this->Html->image('front/home/logo-2.png', array('alt' => ''));  ?>
                                    <?php
                                    if($job['Job']['logo']) {
                                        $path = UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'];
                                        if (file_exists($path) && !empty($job['Job']['logo'])) {
                                            echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $job['Job']['logo'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
                                        } else {
                                            echo $this->Html->image('front/no_image_user.png');
                                        }
                                    } else{
                                          $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $job['Job']['user_id']));
                                        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
                                        if (file_exists($path) && !empty($logo_image)) {
                                            echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image, array('escape' => false, 'rel' => 'nofollow'));
                                        } else {
                                            echo $this->Html->image('front/no_image_user.png');
                                        }
                                    }
                                    ?>
                                </a>
                            </div>
                                <div class="job-locations">
                                    <p><i><?php echo $this->Html->image('front/home/location-icon.png', array('alt' => '')); ?></i>
                                    <a href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php echo $job['Job']['job_city'] ? $job['Job']['job_city'] : 'N/A'; ?></a>
                                    </p>
                                </div>
                                <div class="job-times">
                                    <p><i class="fa fa-calendar" aria-hidden="true"></i><?php
                                        $now = time(); // or your date as well
                                        $your_date = strtotime($job['Job']['created']);
                                        $datediff = $now - $your_date;
                                        $day = round($datediff / (60 * 60 * 24));
                                        echo $day == 0 ? __d('user', 'Today', true) : $day . ' ' . __d('user', 'Days ago', true);
                                        ?></p>
                                </div>
                            </div>
                            
                           
<!--                            <div class="timing ml-auto">
                                <div class="list_bot_boox_col">
                                   
                                </div>
                            </div>-->

                        </div>
                    </div>
                </div>


            </div>
            <?php
            $count++;
        }
        ?>
    </div>

    <?php //echo $this->Form->end();               ?>

    <div class="paging pagingsrt"  style="width:100%;">
        <div class="paging" style="width:100%;">
            <div class="noofproduct">
                <?php
                echo $this->Paginator->counter(
                        '<span>' . __d('user', 'No. of Records', true) . ' </span><span class=""  style="data[0].">{:start}</span><span> - </span><span class=""  style="data[0].">{:end}</span><span> ' . __d('user', 'of', true) . ' </span><span class=""  style="data[0].">{:count}</span>'
                );
                ?> 
            </div>

            <div class="pagination">
                <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));         ?> 
                <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
    <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow'));        ?> 

            </div>	
        </div>
    </div>

<?php } else { ?>
    <div class="listing_box_full">
        <div class="listing_full_row listing_full_row_bg no-deta">
            <div class="nomatching"> 
                <h1><?php echo __d('user', 'There are no jobs matching for your search criteria.', true); ?></h1>
                <h3><?php echo __d('user', 'Please searched with other options.', true); ?></h3>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    function jobApply(id) {
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/applypop/" + id,
            cache: false,
            data: {'actionc': 'jobApply'},
            beforeSend: function () {
                $('#loaderID').show();
            },
            complete: function () {
                $('#loaderID').hide();
            },
            success: function (result) {

                $('#applypop').html(result);
                $('#applypop').addClass("popupc");


            }
        });
    }
    function closepopJob() {
        $('#applypop').removeClass("popupc");
        $('#applypop').html("");
    }
</script>

<div  id="applypop"></div>