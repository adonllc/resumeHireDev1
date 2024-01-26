<script src="https://maps.googleapis.com/maps/api/js" type="text/javascript"></script>
<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#contactUs").validate();
          $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_ ]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");
    });

</script>


<div class="clr"></div>
<article class="no_apid new_soidf">
    
    <div class="iner_pages_formate_box">
        <div class="wrapper">
            <div class="iner_form_bg_box">
                <div class="top_page_name_title">
                    <div class="page_name_boox"><span><h1><?php echo __d('home', 'All companies', true) ?></h1></span></div>            
            </div>
            <div class="clear"></div>
            
                                            <?php 
                                            if ($newJobrecuirer) {
                                                ?><?php
                                                foreach ($newJobrecuirer as $userdetails) {

                                                    ?>
            <div class="sadsattt">
                                        <div class="idtyc">
                                                    <div class="candidateDiv"><div class="page_name_boox page_name_boox_small"><span><?php echo $this->Html->link(ucfirst($userdetails['User']['company_name']), array('controller' => 'candidates', 'action' => 'companyprofile', 'slug' => $userdetails['User']['slug']), array()); ?></span>
                                                            <?php
                                                            if ($userdetails['User']['verify'] == 1) {
                                                                ?><span class="verifed" title="Verified"><?php echo $this->Html->image('front/verified_green.png'); ?></span><?php
                                                                }
                                                                ?>
                                                        </div>
                                                        <div class="ful_row_ddta">

                                                            <span class="blue"><i class="fa fa-map-marker"></i> <?php echo __d('home', 'Location', true); ?>:</span><span class="grey">
                                                                <?php
                                                                echo $userdetails['User']['address'] ? $this->Text->truncate($userdetails['User']['address'], 50, array('html' => true)) : 'N/A';
                                                                ?>
                                                            </span>
                                                            <span class="blue"><i class="fa fa-building"></i> <?php echo __d('home', 'About company', true); ?>:</span><span class="grey">
                                                                <?php echo $userdetails['User']['company_about'] ? $this->Text->truncate($userdetails['User']['company_about'], 45, array('html' => true)) : "N/A"; ?>
                                                            </span>
                                                        </div>  


                                                    </div> 
                                            </div>
                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        
            </div>
        </div>
    </div>

</article>