<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die; ?>
<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                    <div class="info_dv">
                        <div class="heads">View Payment Information</div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    
                             <div class="form_lst">
                                <label>Job Title</label>
                                <span class="rltv"><em><?php echo $_SESSION['data']['title']; ?> </em></span>
                             </div>
                             <div class="form_lst">
                                <label>Job Type</label>
                                <span class="rltv"><em><?php echo $site_title.' '.ucfirst($_SESSION['data']['type']); ?> </em></span>
                             </div>
                            <div id="updatePrice">
                                <?php echo $this->element('updateprice');?>
                               
                            </div>
                            
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function applyCode(){

    if($('#JobPromoCode').val() == ''){
        $('#JobPromoCode').addClass('error');
        return;
    }    
    
    $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/applycode/"+$('#JobPromoCode').val(),
            cache: false,
            //data : $('#promocode').serialize(),
            beforeSend: function(){ $("#subTypeLoader").show(); },
            complete: function(){ $("#subTypeLoader").hide();},
            success: function(result) {
                $("#updatePrice").html(result);
            }
        });
}
function removeCoupon(){
    $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/applycode/",
            cache: false,
            //data : $('#promocode').serialize(),
            beforeSend: function(){ $("#subTypeLoader").show(); },
            complete: function(){ $("#subTypeLoader").hide();},
            success: function(result) {
                $("#updatePrice").html(result);
            }
        });
}
</script>




