<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                    <div class="info_dv">
                        <div class="heads">Membership</div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    
                           
                           
                           
                           <div class="select_plan">
                           
                           <div class="plan_blk">
                           
                           <input type="radio" id="memership_idone"  name="membership" />
                           <label class="pn_selct" for="memership_idone">
                               <div class="plan_name">Standard Job</div>
                               <div class="plan_price">$110</div>
                               <div class="pna_dtls">
                                   <ul>
                                    <li><div>30 Day Listing</div> </li>
                                    <li><div>Unlimited Changes</div></li>
                                    <li><div>Visable of a number of devices</div></li>
                                    <li><div>2 Weeks on Email chain to signed up job seekers</div></li>
                                   </ul>                               
                               </div>  
                               
                           </label>
                           
                           </div>
                            
                           
                            <div class="plan_blk">
                           
                           <input type="radio" id="memership_idtwo" name="membership" />
                           <label class="pn_selct" for="memership_idtwo">
                               <div class="plan_name">Hot Job</div>
                               <div class="plan_price">$180</div>
                               <div class="pna_dtls">
                                   <ul>
                                    <li><div>30 Day Listing</div> </li>
                                    <li><div>Unlimited Changes</div></li>
                                    <li><div>Visable of a number of devices</div></li>
                                    <li><div>30 Day email chain to signed up job seekers</div> </li>
                                    <li><div>Social Media Share / Sync </div></li>
                                    <li><div>Logo and website link</div></li>
                                   </ul>                               
                               </div>  
                               
                           </label>
                           
                           </div>
                            
                           </div>
                            
                            <div class="inputy">
                            
                            <input type="submit" class="input_btn" />
                            
                            </div>
                            
                            
                            
                            
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




