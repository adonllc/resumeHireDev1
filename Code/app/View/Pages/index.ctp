<div class="mid_prt">
  <article class="article_dv">
    <div class="wapper">
     <div class="logo">
            <?php echo $this->Html->link($this->Html->image('front/logo.png', array('alt' => 'Logo')), '/', array('escape' => false,'rel'=>'nofollow')); ?>
     </div>

       <div class="countiner">
            <div class="left_sid1">
                <div class="product_list_box">
                    <div class="ult_dt">
                        <div class="ulti_img">
                            <span class="ult_txt"> 
                              
                                <h1><?php 
                                if (!empty($pagedetails))
                                echo $pagedetails['Page']['static_page_title'];
                                else 	
                                	echo "Page Not Found !";?>
                                </h1>
                            </span>
                           
                        </div>
                    </div>

                    <div class="main_box_slide1">
                        <div class="top_bg_box1"></div>
                        <div class="mdl_bg_box1">
                            <?php
                           
                            if (!empty($pagedetails) && isset($pagedetails['Page']['static_page_title'])) {
                                ?>
                                <p>
                                    <?php
                                    if (isset($_SESSION['Config']['language']) && $_SESSION['Config']['language'] == 'pt') {
                                        echo $pagedetails['Page']['static_page_description_portuguese'];
                                    } else {
                                        echo $pagedetails['Page']['static_page_description'];
                                    }
                                    ?></p>
                            <?php } else { ?>
                               
                                    <?php
                                    if (isset($under) && $under == '1') {
                                        echo $this->Html->image('front/not_found_.png');
                                    } else {
                                        if (isset($_SESSION['Config']['language']) && $_SESSION['Config']['language'] == 'pt') {
                                            echo $this->Html->image('front/not_found_.png', array('width' => 900));
                                        } else {
                                            echo $this->Html->image('front/not_found_.png', array('width' => 900));
                                        }
                                    }
                                    ?>
                              
                            <?php } ?>
                        </div>
                        <div class="btm_bg_box1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
</div>