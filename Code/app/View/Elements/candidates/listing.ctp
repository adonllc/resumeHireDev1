<?php if ($candidates) { ?>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:0px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'candidates', 'action' => 'listing', $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
    ?>
    <?php echo $this->Form->create("Job", array("url" => "listing", "method" => "Post")); ?>
    <div class="listing_box_full">
        <div class="paging">
            <div class="noofproduct">
                <?php
                echo $this->Paginator->counter(
                        '<span>'.__d('user', 'No. of Records', true).' </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="badge-gray">{:count}</span>'
                );
                ?> 
            </div>
            <div class="pagination">
                <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));         ?> 
                <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
    <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow'));        ?> 

            </div>	
        </div>


        <div class="listing_table_data">
            <div class="listing_table_data_table">
                <div class="listing_table_data_row listing_table_data_row_header">
                    <div class="listing_table_data_cols">

                    </div>  
                    <div class="listing_table_data_cols">
                        <?php echo __d('user', 'Name', true);?>
                    </div>  
                    <div class="listing_table_data_cols">
                        <?php echo __d('user', 'Skills', true);?>
                    </div>  
                    <div class="listing_table_data_cols">
                        <?php echo __d('user', 'Contact Number', true);?>
                    </div>  
                    <div class="listing_table_data_cols dfsgdsgbghghh">
                        <?php echo __d('user', 'Experience', true);?>
                    </div>                    
                </div>

                <?php
                $count = 1;
                global $totalexperienceArray;
                foreach ($candidates as $candidate) {
                    if ($count % 2 == 0) {
                        $class = 'listing_full_row_bg';
                    } else {
                        $class = '';
                    }
                    ?>
                    <div class="listing_table_data_row">
                        <div class="listing_table_data_cols listing_table_data_cols_img">
                            <?php
                            $profile_image = $candidate['User']['profile_image'];
                            $path = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $profile_image;
                            if (file_exists($path) && !empty($profile_image)) {
                                // echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $profile_image, array('escape' => false,'rel'=>'nofollow'));
                                echo $this->Html->link($this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_THUMB_PROFILE_IMAGE_PATH . $candidate['User']['profile_image'] . "&w=50&zc=1&q=100", array('escape' => false,'rel'=>'nofollow')), array('controller' => 'candidates', 'action' => 'profile', $candidate['User']['slug']), array('escape' => false,'rel'=>'nofollow'));
                            } else {
                                echo $this->Html->link($this->Html->image('front/no_image_user.png',array('style'=>'width:50px')), array('controller' => 'candidates', 'action' => 'profile', $candidate['User']['slug']), array('escape' => false,'rel'=>'nofollow'));
                            }
                            ?>
                        </div>  
                        <div class="listing_table_data_cols">
                            <?php echo $this->Html->link(ucfirst($candidate['User']['first_name']) . ' ' . ucfirst($candidate['User']['last_name']), array('controller' => 'candidates', 'action' => 'profile', $candidate['User']['slug']), array()); ?>
                        </div>  
                        <div class="listing_table_data_cols">
                            <?php
                            if ($candidate['User']['skills'] != '') {
                                echo str_replace(',', ', ',$candidate['User']['skills']);
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div> 
                        <div class="listing_table_data_cols">
                            <?php
                            if ($candidate['User']['contact'] != '') {
                                echo $candidate['User']['contact'];
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div> 
                        <div class="listing_table_data_cols">
                            <?php
                            if ($candidate['User']['total_exp'] != '') {
                                echo $totalexperienceArray[$candidate['User']['total_exp']];
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div> 
                    </div>

                    <?php
                    $count++;
                }
                ?>





            </div>
        </div>


    </div>
    <?php echo $this->Form->end(); ?>
    <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>'.__d('user', 'No. of Records', true).'</span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="badge-gray">{:count}</span>'
            );
            ?> 
        </div>
        <div class="pagination">
                <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));         ?> 
                <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
                <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
    <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow'));        ?> 

            </div>	
    </div>
<?php } else { ?>
    <div class="listing_box_full">
        <div class="listing_full_row listing_full_row_bg">
            <?php echo __d('user', 'No record found.', true);?>
        </div>
    </div>
<?php } ?>
