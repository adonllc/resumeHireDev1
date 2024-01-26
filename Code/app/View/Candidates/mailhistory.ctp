
<script>
    function checkAll(ele){
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
}
function isAllSelect(frmObject){
    var flgChk = 0;
    for(i=1; i<frmObject.chkRecordId.length; i++)
    {
        if(frmObject.chkRecordId[i].checked == false)
        {
            flgChk = 1;
            break;
        }
    }
    if(flgChk == 1){
        frmObject.chkRecordId[0].checked = false;
    }else{
        frmObject.chkRecordId[0].checked = true;
    }
}
    function mailStatusRead() {
        $('#userStatus').val(1);
        getJobList();
        return false;
    }
    function mailStatusUnread() {
        $('#userStatus').val(2);
        getJobList();
        return false;
    }
    function mailStatusDelete() {
        $('#userStatus').val(0);
        getJobList();
        return false;
    }

    function getMailList() {

        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH . '/candidates/mailhistory' ; ?>",
            cache: false,
            data: $('#mailFrom').serialize(),
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

    }
</script>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-mail-boxes"><h2><i><?php echo $this->Html->image('front/home/mail-history-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Mail History', true); ?></span></h2>
                        </div>
                        <div class="information_cntn payment-history-bx">

                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">
                                <?php echo $this->Form->create('mail', array('url' => 'management', 'method' => 'POST', 'name' => 'mailFrom', 'enctype' => 'multipart/form-data', 'id' => 'mailFrom')); ?>
<!--                                <div class="tabs"> 
                                    <?php // if ($mails) {
                                    ?>
                                        <ul>
                                            <li><input name="chkRecordId" value="0" onclick="checkAll(this)" type='checkbox' class="checkall" /></li>
                                            <li><?php // echo $this->Html->link(__d('user', 'Read', true), 'javascript:void(0);', array('onclick' => 'mailStatusRead();', 'class' => 'active')); ?></li>
                                            <li><?php // echo $this->Html->link(__d('user', 'Unread', true), 'javascript:void(0);', array('onclick' => 'mailStatusUnread();', 'class' => 'active')); ?></li>
                                            <li><?php // echo $this->Html->link(__d('user', 'Delete', true), 'javascript:void(0);', array('onclick' => 'mailStatusDelete();', 'class' => 'active')); ?></li>
                                        </ul>
                                    <?php // } ?>
                                    <?php echo $this->Form->input('User.type', array('type' => 'hidden', 'value' => '0', 'id' => 'userStatus')); ?>
                                </div>-->
                                <?php echo $this->Form->end(); ?>

                                <div class="job_scroll">
                                    <div id="loaderID" style="display:none;position:absolute;margin-left:0;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                    <div id='listID'>

                                        <?php echo $this->element('candidates/mail'); ?>    

                                    </div>

                                </div>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




