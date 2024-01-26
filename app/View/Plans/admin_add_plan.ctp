<script type="text/javascript">
    $(document).ready(function() {
       $.validator.addMethod("age", function(value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Age is not valid.");
        $("#addstrategy").validate();
        
        
        $('.test-size').click(function() {
            var size_checked = $(".test-size input:checked").length;
            if (size_checked == 0) {
                $('#ProductTotalSize').val('');
            } else {
                $('#ProductTotalSize').val(size_checked + ' attribute selected');
            }
        });
    });
    function updatetype(){
       if($('#PlanType').val() != ''){ 
            $('#typemm').html(' (in '+$('#PlanType').val()+')');
       }else{
          $('#typemm').html(''); 
       }
    }
    function updateuserplan(){
        $('#addedAsset1').remove();
        $('#addedAsset2').remove();
        $('#addedAsset3').remove();
        $('#addedAsset4').remove();
        $('#addedAsset5').remove();
       if($('#userplan').val() == 'jobseeker'){ 
            $('#employer_feature').hide();
            $('#jobseeker_feature').show();
       }else{
          $('#employer_feature').show(); 
          $('#jobseeker_feature').hide(); 
       }
    }
</script>
<?php

$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-dollar"></i> Plan List » ', array('controller' => 'Plans', 'action' => 'index',''), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Add Plan ', 'javascript:void(0)', array('escape' => false));
?>

<!-- main content start -->
<?php echo $this->Form->create(Null, array('method' => 'POST', 'name' => 'addstrategy', 'id' => 'addstrategy')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Plan</h4>
                <?php echo $this->Session->flash(); ; ?>
                <section class="panel">
                    <header class="panel-heading">
                        Plan Details:
                    </header>
                    <div class="panel-body assets" id="addto">
                        <div class="form-group ">
                            <label class="col-sm-3 control-label plennn">Plan Name <div class="required_field">*</div></label>
                            <div class="col-sm-9 plennndd" >
                                <?php echo $this->Form->text('Plan.plan_name', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label plennn">Type <div class="required_field">*</div></label>
                            <div class="col-sm-9 plennndd" >
                                <?php 
                                global $planType;
                                echo $this->Form->select('Plan.type',$planType, array('empty' => 'Select plan type', 'class' => "form-control required", 'onchange'=>'updatetype()')) ?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label plennn">User Plan <div class="required_field">*</div></label>
                            <div class="col-sm-9 plennndd" >
                                <?php 
                                $planType=array('employer'=>'Employer','jobseeker'=>'Jobseeker');
                                echo $this->Form->select('Plan.planuser',$planType, array('empty' => 'Select User Plan', 'class' => "form-control required",'id'=>'userplan' ,'onchange'=>'updateuserplan()')) ?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label plennn">Time Period<spa id="typemm"></spa> <div class="required_field">*</div></label>
                            <div class="col-sm-9 plennndd" >
                                <?php echo $this->Form->text('Plan.type_value', array('min' => '1', 'label' => '', 'div' => false, 'class' => "form-control required digits")) ?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label plennn">Plan Amount (<?php echo CURR; ?>) <div class="required_field">*</div></label>
                            <div class="col-sm-9 plennndd" >
                                <?php echo $this->Form->text('Plan.amount', array('min' => '0', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required number")) ?>
                            </div>
                        </div>
                        <div class="form-group" id="employer_feature" style="display: <?php echo isset($this->request->data['Plan']['planuser']) && $this->request->data['Plan']['planuser'] =='employer'?'block':'none'; ?>">
                            <label class="col-sm-3 control-label plennn">Select Features <div class="required_field">*</div></label>
                            <?php
                                $vall = '';
                                global $planFeatuers;
                                $empplanFeatuers = $planFeatuers;
                                unset($empplanFeatuers[4]);
                            ?>
                            
                            <div class="col-sm-9 plennndd" >
                                <div id="size-filter" style="float:left;">
                                    <input type="hidden" id="isSelect" value="0">   
                                    <input type="text" id="ProductTotalSize" placeholder="Select Attributes" readonly="readonly" autocomplete="off" onclick="$('#sizes-dropdown').toggle();" class="form-control required hidebbb" name="data[Plans][sselectedassets]" value="<?php echo $vall;?>">               
                                    <div class="sizes-drop" style="display: none1;" id="sizes-dropdown">
                                        <div class="crooss crsshide hidebbb"><span onclick="$('#sizes-dropdown').toggle();" >X</span></div>
                                        <div class="sizemorescroll">
                                            <div class="cloth_size"> 
                                                <?php if($empplanFeatuers){
                                                    $assetArray = array();
                                                    $oldAssets = array();
                                                    foreach($empplanFeatuers as $node => $value){
                                                        $aid = $node;
                                                        $aname = $value;
                                                        $assetArray[$aid] = $aname;
                                                        $aname = str_replace('"', '', $aname);
                                                        $checked = '';
                                                        if(in_array($aid, $oldAssets)){
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="des_box_cont test-size"><input onclick="addAsset(<?php echo $aid;?>,'<?php echo $aname;?>')" <?php echo $checked;?> type="checkbox" id="StrategyAsset<?php echo $node;?>" value="<?php echo $node;?>" name="data[Plan][feature_ids][]"><label><?php echo $value;?></label></div>
                                                    <?php }
                                                } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="jobseeker_feature" style="display: <?php echo isset($this->request->data['Plan']['planuser']) && $this->request->data['Plan']['planuser'] =='jobseeker'?'block':'none'; ?>">
                            <label class="col-sm-3 control-label plennn">Select Features <div class="required_field">*</div></label>
                            <?php
//                                $vall = '';
//                               $planFeatuers = array('4' => 'Number of Job Apply');
                               $jobseeplanFeatuers = $planFeatuers;
                                unset($jobseeplanFeatuers[1]);
                                unset($jobseeplanFeatuers[2]);
                                unset($jobseeplanFeatuers[3]);
                                 unset($jobseeplanFeatuers[5]);
                            ?>
                            
                            <div class="col-sm-9 plennndd" >
                                <div id="size-filter" style="float:left;width:100%">
                                    <input type="hidden" id="isSelect" value="0">   
                                    <input type="text" id="ProductTotalSize" placeholder="Select Attributes" readonly="readonly" autocomplete="off" onclick="$('#sizes-dropdown').toggle();" class="form-control required hidebbb" name="data[Plans][sselectedassets]" value="<?php echo $vall;?>">               
                                    <div class="sizes-drop" style="display: none1;" id="sizes-dropdown">
                                        <div class="crooss crsshide hidebbb"><span onclick="$('#sizes-dropdown').toggle();" >X</span></div>
                                        <div class="sizemorescroll">
                                            <div class="cloth_size"> 
                                                <?php if($jobseeplanFeatuers){
                                                    $assetArray = array();
                                                    $oldAssets = array();
                                                    foreach($jobseeplanFeatuers as $node => $value){
                                                        $aid = $node;
                                                        $aname = $value;
                                                        $assetArray[$aid] = $aname;
                                                        $aname = str_replace('"', '', $aname);
                                                        $checked = '';
                                                        if(in_array($aid, $oldAssets)){
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="des_box_cont test-size"><input onclick="addAsset(<?php echo $aid;?>,'<?php echo $aname;?>')" <?php echo $checked;?> type="checkbox" id="StrategyAsset<?php echo $node;?>" value="<?php echo $node;?>" name="data[Plan][feature_ids][]"><label><?php echo $value;?></label></div>
                                                    <?php }
                                                } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-3 control-label plennn"><div class="required_field"></div></label>
                        <div class="col-sm-9 plennndd" >
                            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Reset', array('controller' => 'plans', 'action' => 'addPlan'), array('escape' => false,'class'=>'btn btn-danger')); ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        
    </section>
</section>
<?php echo $this->Form->end(); ?>
<!-- main content end -->
<script>
   function preventChar(evt,aid){
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 46 || charCode > 57)){
            return false;
        }
    }
   
function addAsset(aid, aname){
    
    if(aid == 1 || aid == 2 || aid == 4 || aid == 5){
    if($("#StrategyAsset"+aid).is(':checked')){
//        alert(aid);
       
            $("#addto").append('<div class="form-group" id="addedAsset'+aid+'">'+
                    '<label class="col-sm-3 control-label plennn">'+aname+' <div class="required_field">*</div></label>'+
                    '<div class="col-sm-9 plennndd">'+
                        '<div class="test_c">'+
                            '<div class="test_c_text"><input type="text" id="fvaltext'+aid+'" class="form-control required digits cccccc" min="1" onkeypress="return preventChar(event)" name="data[Plan][selectFet]['+aid+']"></div>  <div class="test_c_ckk"><input onclick="setunlimited('+aid+')" id="fvaltextdd'+aid+'" name="data[Plan][seleccheckbox]['+aid+']" type="checkbox" value="1" > Unlimited </div>'+
                        '</div>'+
                    '</div>'+
                 '</div>');
        }else{
            $('#addedAsset'+aid).remove();
        }
    }
}

function setunlimited(fid){
    if($('#fvaltextdd'+fid).is(':checked')){
        $('#fvaltext'+fid).removeClass('required').attr('readonly', true).val('');
    }else{
        $('#fvaltext'+fid).addClass('required').attr('readonly', false);
    }
}
</script>