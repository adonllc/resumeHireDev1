<script>
    /*  $(document).ready(function () {
     $("input[name$='type']").click(function () {
     var test = $(this).val();
     //alert(test);
     if (test == 'bronze')
     {
     $("#butto").show();
     } else {
     $("#butto").hide();
     }
     
     });
     });
     */
</script>
<script>
    $(document).ready(function () {
        $("#bronzo_plain").click(function () {
            $("#gold_plain").removeClass("selected");
            $("#silver_plain").removeClass("selected");
            $("#bronzo_plain").addClass("selected");
        });

        $("#gold_plain").click(function () {
            $("#bronzo_plain").removeClass("selected");
            $("#silver_plain").removeClass("selected");
            $("#gold_plain").addClass("selected");
        });

        $("#silver_plain").click(function () {
            $("#gold_plain").removeClass("selected");
            $("#bronzo_plain").removeClass("selected");
            $("#silver_plain").addClass("selected");
        });

    });
</script>

<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="info_dv">
                        <div class="heads heads_padgin">Select Job Type</div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    

                            <?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'id' => 'addCat'));?>

                            <?php 
                            $sjo = '';
                            $hjo = '';
                            $gjo = '';
                            if(isset($_SESSION['type']) && $_SESSION['type'] == 'bronze'){
                                $sjo = 'checked';
                            }elseif(isset($_SESSION['type']) && $_SESSION['type'] == 'silver'){
                                $hjo = 'checked';
                            }elseif(isset($_SESSION['type']) && $_SESSION['type'] == 'gold'){
                                $gjo = 'checked';
                            }
                            
                            ?>
                            <!--<div class="inputy_link_top">
                                <input type="submit" class="input_btn_yello" value="Continue" />
                            </div>-->
                             <?php //echo $this->element('banner_advertisement'); ?>    

                            <div class="select_plan">
                                <div class="plan_blk" id="bronzo_plain">
                                    <input type="radio" id="memership_idone"  name="type"  value="bronze" <?php echo $sjo;?>  />
                                    <label class="pn_selct" for="memership_idone">
                                        <div class="plan_name"> Bronze </div>
                                        <div class="price_inner_section">
                                            <div class="plan_price">
                                                <?php
                                                if($planInfo['Admin']['bronze'] > 0 ){
                                                    //echo CURRENCY.$planInfo['Admin']['bronze'];?> <!--<span>Exc GST</span>-->
                                               <?php  }else{
                                                  // echo 'Free';
                                               }    ?>
                                            </div>
                                            <div class="pna_dtls">
                                                <ul>
                                                    <li><div>30 Day Listing</div> </li>
                                                    <!-- <li><div>Emailed to Seekers - 1 Week</div></li>-->
                                                    <li><div>Unlimited Changes</div></li>
                                                    <li><div>1 Selling Point </div></li>
                                                </ul>                               
                                            </div>  
                                            <input type="submit" value="Choose Plan" class="choose_plain_btn" />
                                        </div>


                                    </label>
                                </div>


                                <div class="plan_blk selected" id="gold_plain">
                                    <input type="radio" id="memership_idthree" name="type" checked="checked" value="gold" <?php echo $gjo;?> />
                                    <label class="pn_selct" for="memership_idthree">
                                        <div class="plan_name"> Gold</div>
                                        <div class="price_inner_section">
                                            <div class="plan_price">
                                                <?php
                                                 if($planInfo['Admin']['gold'] > 0){
                                                   // echo CURRENCY.$planInfo['Admin']['gold'];?> <!--<span>Exc GST</span>-->
                                                 <?php  }else{
                                                    // echo 'Free';
                                                 } ?>    

                                            </div>
                                            <div class="pna_dtls">
                                                <ul>
                                                    <li><div>30 Day Listing</div> </li>
<!--                                                    <li><div>Emailed to Seekers - 4 Weeks</div></li>-->
                                                    <li><div>Unlimited Changes</div></li>
                                                    <li><div>3 Selling Points </div></li>
                                                    <li><div>Logo Visible on Posting</div></li>
<!--                                                    <li><div>“Hot Job” Customised Advertising</div></li>-->
                                                    <li><div>Highlighted Background to Stand Out</div></li>
                                                </ul>                               
                                            </div>  
                                            <input type="submit" value="Choose Plan" class="choose_plain_btn" />
                                        </div>
                                    </label>
                                </div>
                                <div class="plan_blk" id="silver_plain">

                                    <input type="radio" id="memership_idtwo" name="type"   value="silver" <?php echo $hjo;?> />
                                    <label class="pn_selct" for="memership_idtwo">
                                        <div class="plan_name"> Silver</div>
                                        <div class="price_inner_section">
                                            <div class="plan_price">
                                                <?php 
                                                 if($planInfo['Admin']['silver'] > 0 ){
                                                       // echo CURRENCY.$planInfo['Admin']['silver'];?> <!--<span>Exc GST</span>-->
                                                 <?php  } else{
                                                     //echo 'Free';
                                                 } ?>      
                                            </div>
                                            <div class="pna_dtls">
                                                <ul>
                                                    <li><div>30 Day Listing</div> </li>
                                                    <!-- <li><div>Emailed to Seekers - 2 Weeks</div></li>-->
                                                    <li><div>Unlimited Changes</div></li>
                                                    <li><div>3 Selling Points </div></li>
                                                    <li><div>Logo Visible on Posting</div></li>
                                                </ul>                               
                                            </div>  
                                            <input type="submit" value="Choose Plan" class="choose_plain_btn" />
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!--<div class="inputy btn_center">
                                <input type="submit" class="input_btn_yello" value="Continue" />
                            </div>-->
                            <?php echo $this->Form->end(); ?>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




