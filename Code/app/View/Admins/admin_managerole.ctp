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
    
    function checkSubRole(rId){ 
        if($('#StrategyAsset'+rId).is(':checked')){ 
            $('.sbrole'+rId).prop('checked', true); 
        }else{ 
            $('.sbrole'+rId).prop('checked', false); 
        }
    }
    
    function checkSubRoleSub(mId, sId){
        if($('#sub'+mId+'_'+sId).is(':checked')){ 
            $('#StrategyAsset'+mId).prop('checked', true); 
        }else if($('.sbrole'+mId+':checked').length == 0){
            $('#StrategyAsset'+mId).prop('checked', false); 
        }
    }
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-user"></i> Subadmin List » ', array('controller' => 'admins', 'action' => 'manage'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Manage Roles ', 'javascript:void(0)', array('escape' => false));
?>
<?php //pr($this->data);exit;?>
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
                <h4 style="margin-left:15px" class="m-bot15">Manage Roles </h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Manage Roles :
                    </header>
                    <div class="panel-body assets" id="addto">
                        <div class="form-group">
                            <label class="col-sm-2 control-label plennn role_ll">Select Roles <div class="required_field">*</div></label>
                            <?php
//                            /pr($this->data['Plan']['feature_ids']);
                                $vall = '';
                                global $subadminroles;
                                global $subroles;
                                $oldSubRoles = array();
                                if(isset($this->data['Admin']['role_ids']) && $this->data['Admin']['role_ids'] !=''){
                                    $oldAssets = explode(',', $this->data['Admin']['role_ids']);
                                }
                                if(isset($this->data['Admin']['sub_role_ids']) && $this->data['Admin']['sub_role_ids'] !=''){
                                    $oldSubRoles = json_decode($this->data['Admin']['sub_role_ids'], true);
                                }
                            ?>
                            
                            <div class="col-sm-10 plennndd role_rr" >
                                <div id="size-filter" style="float:left;">
                                    <input type="hidden" id="isSelect" value="0">   
                                    <input type="text" id="ProductTotalSize" placeholder="Select Role" readonly="readonly" autocomplete="off" onclick="$('#sizes-dropdown').toggle();" class="form-control required hidebbb" name="data[Admin][sselectedassets]" value="<?php echo $vall;?>">               
                                    <div class="sizes-drop" style="display: none1;" id="sizes-dropdown">
                                        <div class="crooss crsshide"><span onclick="$('#sizes-dropdown').toggle();" >X</span></div>
                                        <div class="sizemorescroll">
                                            <div class="cloth_size"> 
                                                <?php 
                                                
                                                if($subadminroles){
                                                    $assetArray = array();
                                                    foreach($subadminroles as $node => $value){
                                                        $aid = $node;
                                                        $checked = '';
                                                        if(isset($oldAssets) && in_array($aid, $oldAssets)){
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="des_box_cont test-size mmbtm">
                                                            <div class="main_role"><input <?php echo $checked;?> type="checkbox" id="StrategyAsset<?php echo $node;?>" value="<?php echo $node;?>" name="data[Admin][role_ids][]" onclick="checkSubRole(<?php echo $aid;?>)"><label><?php echo $value;?></label></div>
                                                            <div class="sub_role">
                                                                <?php
                                                                    foreach($subroles[$aid] as $key=>$val){ 
                                                                        $checkedSub = '';
                                                                        if(array_key_exists($aid, $oldSubRoles) && in_array($key, $oldSubRoles[$aid])){
                                                                           $checkedSub = 'checked'; 
                                                                        }
                                                                        ?>
                                                                        <div class="sub_role_c"><input <?php echo $checkedSub;?> class="sbrole<?php echo $aid;?>" type="checkbox" id="sub<?php echo $aid.'_'.$key;?>" value="<?php echo $key;?>" name="data[Admin][sub_role_ids][<?php echo $aid;?>][]" onclick="checkSubRoleSub(<?php echo $aid.','.$key;?>)"><label><?php echo $val;?></label></div>
                                                                    <?php }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                           </div>  
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <div class="col-sm-2 col-sm-2 control-label">&nbsp;</div>
        <div class="col-lg-9">
            <?php
            echo $this->Form->hidden('Admin.id');
            ?>
             <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'manage'), array('escape' => false,'class'=>'btn btn-danger')); ?>
        </div>
            </div></div>
        <!-- page end -->
    </section>
</section>
<?php echo $this->Form->end(); ?>
<!-- main content end -->
