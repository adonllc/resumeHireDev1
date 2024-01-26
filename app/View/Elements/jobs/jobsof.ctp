<?php if ($jobs) { ?>
    <div class="sadasd sdsdrrrr"> Jobs of  <?php echo $company_name ?> 
    
    
    </div>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:0px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'users', 'action' => 'jobsof', $alpha, $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
    ?>
    <?php echo $this->Form->create("User", array("url" => "viewjobs", "method" => "Post")); ?>
    <div class="listing_box_full newcc">



        <div class="sdasdsadsGt">
            <div class="newc">

                <?php
                $count = 1;
                foreach ($jobs as $candidate) {
                    if ($count == 100) {
                        // echo '</div><div class="listing_table_data_table rights">';
                    }
                    ?>
                    <div class="sadnasdugsadyyyyy">

                        <div class="Ytt sadsd">
                            <?php echo $this->Html->link(ucfirst($candidate['Job']['title']), array('controller' => 'jobs', 'action' => 'detail', 'cat' => $candidate['Category']['slug'], 'slug' => $candidate['Job']['slug'], 'ext' => 'html'), array()); ?>
                        </div>  

                    </div>

                    <?php
                    $count++;
                }
                ?>



            </div>
            <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>No. of Records </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> of </span><span class="badge-gray">{:count}</span>'
            );
            ?> 
        </div>
        <div class="paginations">
            <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false, 'rel' => 'nofollow', 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('User')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('User')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php // echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false, 'rel' => 'nofollow')); ?> 

        </div>	
    </div>

        </div>



    </div>
    <?php echo $this->Form->end(); ?>
    
<?php } else {
    
    ?>
   
    <div class="listing_box_full">
            <div class="listing_full_row listing_full_row_bg neccft">
                <div class="_norett">
                    <div class="_imgt"><?php
                    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $compay_id['User']['profile_image'];
                    if (file_exists($path) && !empty($compay_id['User']['profile_image'])) {
                        ?>

                        <?php
                        echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $compay_id['User']['profile_image'] . "&w=200&zc=1&q=100", array('escape' => false));
                    } else {
                       
                        echo $this->Html->image('front/skyline.svg', array('width' => '100%'));
                    }
                    ?></div>
                 <div class="ful_row_ddtartt">
                        <?php echo $company_name; ?>
                    </div>
                <?php
                $userid = $this->Session->read('user_id');
                if ($userid > 0) {
                    ?>
                   
                    <div class="ful_row_ddtartt">
                        <span class="blue"><i class="fa fa-phone"></i> Contact:</span><span class="grey">
                            <?php
//                        pr($compay_id); exit;
                            echo $compay_id['User']['company_contact'] ? $this->Text->usformat($compay_id['User']['company_contact']) : 'N/A';
                            ?>
                        </span>
                    </div>

                    <div class="ful_row_ddtartt">
                        <span class="blue"><i class="fa fa-user"></i> Contact person:</span><span class="grey">
                            <?php
                            echo $compay_id['User']['contact_person'] ? $compay_id['User']['contact_person'] . ' ' . $compay_id['User']['contact_person_email'] : 'N/A';
                            ?>
                        </span>
                    </div>
                <?php } ?>
                    
                <div class="ful_row_ddtartt">
                   <span class="grey">
                       <i class="fa fa-map-marker"></i> Located at:
                        <?php
                        echo $compay_id['User']['address'] ? $compay_id['User']['address'] . ' ' . $compay_id['User']['postal_code'] : 'N/A';
                        ?>
                    </span>
                </div>
                </div>

                <!--No record found for <?php echo $alpha ?>, please search for another company name or go to <?php echo $this->Html->link('homepage', '/') ?>.-->
            </div>
        </div>
<?php } ?>
