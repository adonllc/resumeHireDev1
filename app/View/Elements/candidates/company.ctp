<?php if ($candidates) { ?>
    <div class="sadasd"> Companies profiles having starting alphabetically - <?php echo $alpha ?> </div>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:0px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'users', 'action' => 'peoplesview', $alpha, $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
    ?>
    <?php echo $this->Form->create("User", array("url" => "peoplesview", "method" => "Post")); ?>
    <div class="listing_box_full newcc">



        <div class="sdasdsadsGt">
            <div class="newc">

                <?php
                $count = 1;
                foreach ($candidates as $candidate) {
                    if ($count == 100) {
                        // echo '</div><div class="listing_table_data_table rights">';
                    }
                    ?>
                    <div class="sadnasdugsadyyyyy">

                        <div class="Ytt sadsd">
                            <?php echo $this->Html->link(ucfirst($candidate['User']['company_name']), array('controller' => 'users', 'action' => 'jobsof','slug' => $candidate['User']['slug']), array());
                             if($candidate['User']['verify'] == 1){
                                                ?><span class="verifed" title="Verified"><?php echo $this->Html->image('front/verified_green.png'); ?></span><?php
                                            }
                            ?>
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
                    '<span>'.__d('user', 'No. of Records', true).' </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="badge-gray">{:count}</span>'
            );
            ?> 
        </div>
        <div class="paginations">
            <?php // echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false, 'rel' => 'nofollow', 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('User')) echo $this->Paginator->prev('Prev', array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('User')) echo $this->Paginator->next('Next', array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false, 'rel' => 'nofollow')); ?> 

        </div>	
    </div>
            </div>
    </div>
    <?php echo $this->Form->end(); ?>
    
<?php } else { ?>
    <div class="listing_box_full">
        <div class="listing_full_row listing_full_row_bg neccft">
            No record found for <?php echo $alpha ?>, please search for another alphabet's or go to <?php echo $this->Html->link('homepage','/') ?>.
        </div>
    </div>
<?php } ?>
