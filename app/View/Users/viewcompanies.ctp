<div class=""></div>
<div class="clear"></div>
<div class="iner_pages_formate_box">
    <div class="container">
        <div class="_new dfds"><?php 
                global $alpha;
                ?><div class="peoplesc"><?php echo __d('user', 'Company title', true);?>: <?php
                foreach($alpha as $alphaAV){
                    $class = "";
                  
                    if($slug == $alphaAV){
                        $class = "active";
                    }
                    echo $this->Html->link($alphaAV,array('controller'=>'users','action'=>'viewcompanies','slug' =>$alphaAV ),array('class'=>$class));
                }

                ?></div><?php
                
                ?></div>
        <div class="_notes">
             <?php echo __d('user', 'Here, the companies profiles of talented youth are listed in an alphabetical manner. Know more about them by going through their jobs.', true);?> 
        </div>
        <div id="listID">
        <?php echo $this->element('candidates/company'); ?>
        </div>
    </div>
</div>