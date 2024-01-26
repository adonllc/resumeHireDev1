<?php
echo $this->Html->css('front/sweetalert.css');
echo $this->Html->script('front/sweetalert.min.js');
echo $this->Html->script('front/sweetalert-dev.js');
?>

<script>
    function completePayment(job_slug) {
        swal({
            title: "",
            text: "<?php echo __d('user', 'Are you sure you want to pay for this job', true); ?> ?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#fccd13",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
        },
                function () {
                    window.location.href = "<?php echo HTTP_PATH; ?>/payments/manualPaynow/" + job_slug;
                });
    }
</script>

<?php if ($mails) { ?>
    <div class="right_child_sec_over">
        <div class="job_content right_child_sec">
            <?php
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID',
                'url' => array('controller' => 'candidates', 'action' => 'mailhistory', $separator),
                'indicator' => 'loaderID'));
            ?>

            <ul class="job_heading mail_job_heading">
                <li><?php echo __d('user', 'User Name', true); ?></li>
                <li><?php echo __d('user', 'Company name', true); ?></li>
                <li><?php echo __d('user', 'Subject', true); ?></li>
                <li><?php echo __d('user', 'Created', true); ?></li>
                <li><?php echo __d('user', 'Action', true); ?></li>
                <!-- <li>Payment status</li>-->
            </ul>
            <?php
            $srNo = 1;
            foreach ($mails as $mail) {
                $mailSlug = $mail['Mail']['slug'];
                ?>
                <ul class="job_list mail_job_heading">
                    <!--<li><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $mail['Mail']['id']; ?>" /></li>-->
                     <li><?php echo $mail['Sender']['company_name']?ucwords($mail['Company']['first_name'] . ' ' . $mail['Company']['last_name']):ucwords($mail['Sender']['first_name'] . ' ' . $mail['Sender']['last_name']) ?></li>
                    <li><?php echo $mail['Company']['company_name']?ucwords($mail['Company']['company_name']):ucwords($mail['Sender']['company_name']) ?></li>
                    <li> <?php $title = $mail['Mail']['subject'] ? $mail['Mail']['subject'] : $mail['Job']['title'];
                echo $this->Html->link($title, array('controller' => 'candidates', 'action' => 'maildetail', $mail['Mail']['slug']));
                ?>
                    </li>
                    <li><?php echo date('d M, Y', strtotime($mail['Mail']['created'])); ?></li>
                    <li class="pay-action">
        <?php echo $this->Html->link('<i class="fa fa-eye"></i>', array('controller' => 'candidates', 'action' => 'maildetail', $mail['Mail']['slug']), array('escape' => false, 'rel' => 'nofollow', 'class' => 'btn btn-info btn-xs')); ?>
        <?php // echo $this->Html->link('<i class="fa fa-trash"></i>', array('controller' => 'candidates', 'action' => 'maildelete', $mail['Mail']['slug']), array('escape' => false, 'rel' => 'nofollow','class'=>'btn btn-info btn-xs'));  ?>
                    </li>



                </ul>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>' . __d('user', 'No. of Records', true) . ' </span><span class="">{:start}</span><span> - </span><span class="">{:end}</span><span> ' . __d('user', 'of', true) . ' </span><span class="">{:count}</span>'
            );
            ?> 
        </div>

        <div class="pagination">
            <?php echo $this->Paginator->first('Prev <i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false, 'rel' => 'nofollow', 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('User')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
    <?php if ($this->Paginator->hasNext('User')) echo $this->Paginator->next('Next <i class="fa fa-angle-right"></i>', array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
    <?php echo $this->Paginator->last('Last <i class="fa fa-angle-double-right"></i>', array('class' => 'last', 'escape' => false, 'rel' => 'nofollow')); ?> 
        </div>	
    </div>
<?php }else { ?>
    <div class="no_found"><?php echo __d('user', 'No record found', true); ?>.</div>
<?php } ?>