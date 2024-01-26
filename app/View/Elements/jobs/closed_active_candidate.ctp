<?php echo $this->Html->script('listing.js'); ?>
<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>
<script type="text/javascript">
    $(document).ready(function($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    });
    
    
    
</script>

<?php if ($candidates) { ?>
<script type="text/javascript">
    var img_path = "<?php echo HTTP_IMAGE; ?>/front";
</script>
<?php echo $this->Html->script('rating/js/jquery.raty.min.js');  ?>
<script>
     
    $(function() {
         <?php foreach ($candidates as $candidate) { ?>
        $('#qtyStar<?php echo $candidate['JobApply']['id'];?>').raty({
            starOn:    'star-on.png',
            starOff:   'star-off.png',
            start: <?php echo $candidate['JobApply']['rating'];?>,
            number    : 5,
            readOnly : true
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
    <?php echo $this->Form->create("JobApply", array("url" => "accdetail", "method" => "Post",'id'=>'sendMail')); ?>

    <div class="detl_content">
        <ul class="detl_heading">
            <li><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></li>
            <li><?php echo $this->Paginator->sort('User.full_name', 'Name'); ?></li>
            <li><?php echo $this->Paginator->sort('JobApply.rating', 'Rating'); ?></li>
            <li>Contact</li>
            <li><?php echo $this->Paginator->sort('JobApply.apply_status', 'Status'); ?></li> 
        </ul>
        <?php foreach ($candidates as $candidate) { ?>
            <ul class="detl_list">
                <li><input type="checkbox" id="listemail" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $candidate['User']['email_address']; ?>" /></li>
                <li>
                    <div class="candi_dtl">
                        
                        <div class="candi_namer">
                            <?php echo $this->Html->link(ucfirst($candidate['User']['first_name']) . ' ' . ucfirst($candidate['User']['last_name']), array('controller' => 'jobs', 'action' => 'candidateDetails',$jobInfo['Job']['slug'],$candidate['User']['slug'])); ?>
                        </div>
                        <div class="candi_desg">
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
<!--                        <div class="candi_lc">Glencore</div>-->
                        <div class="candi_dte"><i class="fa fa-calendar" ></i><em><?php echo date('jS F, Y', strtotime($candidate['JobApply']['created'])); ?></em></div>

                        <div class="candi_options">
                            <?php
                            if ($candidate['User']['cv']) {
                                echo $this->Html->link('Resume', array('controller' => 'candidates', 'action' => 'download', $candidate['User']['cv']));
                                echo '|';
                            }
                            ?>
                            <a href="#info<?php echo $candidate['User']['id']; ?>" rel="facebox" title="View" class="cvr_ltr">Cover Letter</a>
                        </div>
                       

                    </div>
                    <?php if ($candidate['JobApply']['new_status'] == 1) {?>
                    <div class="fdfdrff">New</div>
                    <?php }?>
                </li>
               
                <li class="jobdi"><div class="rating_stars" id="qtyStar<?php echo $candidate['JobApply']['id'];?>"> </div>
                <div id="loaderRating<?php echo $candidate['JobApply']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                </li> 
                
                <li><?php echo $candidate['User']['contact'] ?$this->Text->usformat( $candidate['User']['contact']) : 'N/A'; ?></li>
                <li>
                    <div class="status_select">
                        <?php echo ucfirst($candidate['JobApply']['apply_status']); ?>
                    </div> </li>
            </ul>
        <?php } ?>
        
    </div>
    <div id="actdiv" class="outside">
                <div class="block-footer mogi">
                    <?php echo $this->Form->text('JobApply.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('JobApply.action', array('type' => 'hidden', 'value' => 'email', 'id' => 'action')); ?>
                    <?php echo $this->Ajax->submit("Send Email", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'accdetail',$jobInfo['Job']['slug']), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('email');", 'confirm' => "Are you sure you want to Send Email?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
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
    <div class="detl_content">No Record Found.</div>
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
                <div class="drt">
                    <span></span> 
                    <span></span>   <?php echo nl2br($candidate['User']['cover_letter']); ?><br/>



                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>
