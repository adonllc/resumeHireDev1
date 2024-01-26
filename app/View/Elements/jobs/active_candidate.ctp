<?php echo $this->Html->script('listing.js'); ?>
<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>

<script type="text/javascript">
    
        function getactive(id){
          
            $('.awei').removeClass('newda');
            $('#' + id).addClass('newda');
        }
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        });



        $('#searchApplyCandidate').submit(function () {
            $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/jobs/accdetail/<?php echo $jobInfo['Job']['slug']; ?>",
                                cache: false,
                                data: $('#searchApplyCandidate').serialize(),
                                beforeSend: function () {
                                    $("#loaderID").show();
                                },
                                complete: function () {
                                    $("#loaderID").hide();
                                },
                                success: function (result) {
                                    $('#listID').html(result);
                                }
                            });
                            return false;
                        });

                        $('.search_by_status').click(function () {
                            var status = $(this).attr('status');
                            $.ajax({
                                type: 'POST',
                                url: "<?php echo HTTP_PATH; ?>/jobs/accdetail/<?php echo $jobInfo['Job']['slug']; ?>" + '/' + status,
                                                cache: false,
                                                data: $('#searchApplyCandidate').serialize(),
                                                beforeSend: function () {
                                                    $("#loaderID").show();
                                                },
                                                complete: function () {
                                                    $("#loaderID").hide();
                                                },
                                                success: function (result) {
                                                    $('#listID').html(result);
                                                }
                                            });
                                            return false;
                                        });
                                    });


                                    function updateStatus(data) {
                                        var can_id = $(data).attr('candidate_id');
                                        var status = $(data).val();
                                        if (status == '')
                                        {
                                            status = 'active';
                                        }

                                        $.ajax({
                                            type: 'POST',
                                            url: "<?php echo HTTP_PATH; ?>/jobs/accdetail/<?php echo $jobInfo['Job']['slug']; ?>/<?php echo $status; ?>/<?php echo 'page:' . $this->Paginator->counter('{:start}'); ?> ",
                                            cache: false,
                                            data: {'data[JobApply][keyword]': $('#searchcc').val(), 'data[JobApply][status_change]': status, 'data[JobApply][candidate_id]': can_id},
                                            beforeSend: function () {
                                                $("#loaderID").show();
                                            },
                                            complete: function () {
                                                $("#loaderID").hide();
                                            },
                                            success: function (result) {
//                                                $('#listID').html(result);
                                                window.location.reload();
                                            }
                                        });
                                        return false;

                                    }

                                    function updateStatus11(data) {
                                        var can_id = $(data).attr('candidate_id');
                                        var status = $(data).val();
                                        if (status == '')
                                        {
                                            status = 'active';
                                        }
                                        //$('#loaderIDAct'+can_id).show(); 
                                        window.location.href = "<?php echo HTTP_PATH; ?>/jobApplies/changeCandidateStatus/" + can_id + "/" + status;
//        $.ajax({
//            type : 'POST',
//            url: "<?php //echo HTTP_PATH;   ?>/jobApplies/changeCandidateStatus/"+can_id+"/"+status,
//            cache: false,
//            success: function(result) {
//                $('#loaderIDAct'+can_id).hide();
//            }
//        });
                                    }

</script>

<?php if ($candidates) { ?>
    <script type="text/javascript">
        var img_path = "<?php echo HTTP_IMAGE; ?>/front";
    </script>
    <?php echo $this->Html->script('rating/js/jquery.raty.min.js'); ?>
    <script>

        $(function () {
    <?php foreach ($candidates as $candidate) { ?>
                $('#qtyStar<?php echo $candidate['JobApply']['id']; ?>').raty({
                    starOn: 'star-on.png',
                    starOff: 'star-off.png',
                    start: <?php echo $candidate['JobApply']['rating']; ?>,
                    number: 5,
                    click: function (score, evt) {
                        $("#loaderRating<?php echo $candidate['JobApply']['id']; ?>").show();
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo HTTP_PATH; ?>/jobApplies/updateRating/<?php echo $candidate['JobApply']['id']; ?>" + "/" + score,
                                                cache: false,
                                                success: function (result) {
                                                    $('#loaderRating<?php echo $candidate['JobApply']['id']; ?>').hide();
                                                }
                                            });

                                        }
                                    });
    <?php } ?>


                            });
    </script>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:191px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'accdetail', $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
    ?>
    <?php echo $this->Form->create("JobApply", array("url" => "accdetail", "method" => "Post", 'id' => 'sendMail', 'class' => 'neww_added_claerar')); ?>

    <div class="table_over_auto_div">
        <div class="detl_content">
            <ul class="detl_heading">
                <li><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></li>
                <li><?php echo $this->Paginator->sort('User.full_name', __d('user', 'Name', true)); ?></li>
                <li><?php echo $this->Paginator->sort('JobApply.rating', __d('user', 'Rating', true)); ?></li>
                <li><?php echo __d('user', 'Contact no.', true);?></li>
                <li><?php echo $this->Paginator->sort('JobApply.apply_status', __d('user', 'Status', true)); ?></li> 
            </ul>
            <?php foreach ($candidates as $candidate) { ?>
                <ul class="detl_list">
                    <li class="new_tested"><input type="checkbox" id="listemail" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $candidate['User']['email_address']; ?>" /></li>
                    <li>
                        <div class="candi_dtl">

                            <div class="candi_namer">
                                <?php
                                if ($candidate['User']['id'] != '') {
                                    echo $this->Html->link(ucfirst($candidate['User']['first_name']) . ' ' . ucfirst($candidate['User']['last_name']), array('controller' => 'jobs', 'action' => 'candidateDetails', $jobInfo['Job']['slug'], $candidate['User']['slug']));
                                } else {
                                    echo '<div style="color:red;">Jobseeker Deleted By Admin</div>';
                                }
                                ?>
                            </div>
                            <!--   <div class="candi_desg">
                            <?php
                            $ffff = 1;
                            if ($candidate['User']['exp_year']) {
                                $ffff = 0;
                                echo $candidate['User']['exp_year'];
                                if ($candidate['User']['exp_year'] > 1) {
                                    echo ' Years';
                                } else {
                                    echo ' Year';
                                }
                            }
                            if ($candidate['User']['exp_month']) {
                                echo ' ' . $candidate['User']['exp_month'];
                                if ($candidate['User']['exp_month'] > 1) {
                                    echo ' Months';
                                } else {
                                    echo ' Month';
                                }
                            }

                            if ($ffff) {
                                echo 'N/A';
                            }
                            ?>
                               </div>
                               <div class="candi_lc">Glencore</div>-->
                            <div class="candi_dte"><i class="fa fa-calendar" ></i><em><?php echo date('jS F, Y', strtotime($candidate['JobApply']['created'])); ?></em></div>

                            <div class="candi_options">
                                <?php
                                if ($candidate['User']['cv']) {
                                    echo $this->Html->link('Resume', array('controller' => 'candidates', 'action' => 'download', $candidate['User']['cv']));
                                }
                                ?>
                                <?php
                                if ($candidate['CoverLetter']['description'] != '') {
                                    if ($candidate['User']['cv']) {
                                        echo '|';
                                    }
                                    ?>
                                    <a href="#info<?php echo $candidate['User']['id']; ?>" rel="facebox" title="View" class="cvr_ltr"><i class="fa fa-file-text"></i> Cover Letter</a>
                                <?php } ?>
                            </div>


                        </div>
                        <?php if ($candidate['JobApply']['new_status'] == 1) { ?>
                            <div class="fdfdrff" style="color:#e60479;">New</div>
                            <?php ClassRegistry::init('JobApply')->updateNewStatus($candidate['JobApply']['id']); ?>
                        <?php } ?>
                    </li>

                    <li class="jobdi"><div class="rating_stars" id="qtyStar<?php echo $candidate['JobApply']['id']; ?>"> </div>
                        <div id="loaderRating<?php echo $candidate['JobApply']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                    </li> 

                    <li><?php echo $candidate['User']['contact'] ? $this->Text->usformat($candidate['User']['contact']) : 'N/A'; ?></li>
                    <li>
                        <div class="status_select">
                            <div id="loaderIDAct<?php echo $candidate['JobApply']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                            <?php global $active_option; ?>
                            <?php echo $this->Form->input('JobApply.apply_status', array('type' => 'select', 'default' => $candidate['JobApply']['apply_status'], 'options' => $active_option, 'label' => false, 'div' => false, 'class' => "required", 'empty' => __d('user', 'Select Status', true), 'onChange' => 'updateStatus(this)', 'candidate_id' => $candidate['JobApply']['id'])) ?>
                        </div> </li>
                </ul>
            <?php } ?>

        </div>
    </div>
    <div id="actdiv" class="outside">
        <div class="block-footer mogi">
            <?php echo $this->Form->text('JobApply.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
            <?php echo $this->Form->text('JobApply.action', array('type' => 'hidden', 'value' => 'email', 'id' => 'action')); ?>
            <?php echo $this->Ajax->submit(__d('user', 'Send Email', true), array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'accdetail', $jobInfo['Job']['slug']), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('email');", 'confirm' => "Are you sure you want to Send Email?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
            <?php //echo $this->Form->submit("Send Email", array('div' => false, 'class' => 'btn btn-success btn-cons','onclick'=>'return sendMail();')); ?> 

        </div>
    </div>
    <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>'.__d('user', 'No. of Records', true).' </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="badge-gray">{:count}</span>'
            );
            ?> 
        </div>
        <div class="pagination">
            <?php echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('JobApply')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('JobApply')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow')); ?> 
        </div>	
    </div>
<?php } else { ?>
    <div class="no_found"><?php echo __d('user', 'No record found.', true);?></div>
<?php } ?>






<?php foreach ($candidates as $candidate) { ?>

    <div id="info<?php echo $candidate['User']['id']; ?>"
         style="display: none;">
        <!-- Fieldset -->
        <div class="nzwh-wrapper">
            <fieldset class="nzwh">
                <legend class="nzwh">
                    Cover Letter
                </legend>
                <div class="drt"><?php echo nl2br($candidate['CoverLetter']['description']); ?><br/>
                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>
