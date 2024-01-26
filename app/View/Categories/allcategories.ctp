<div class="middels_bx sitemap_bx">
    <div class="wrapper">
        <div class="iner_form_bg_box">
            <div class="cebter">
                <div class="top_page_name_title">
                    <div class="page_name_boox"><h1><?php echo __d('home', 'All categories', true) ?></h1></div>
                </div>
                <div class="clear"></div>
                <div class="edit_boxes edit_boxesdre inpfil">

                    <div class="link_menu link_menu_sitemap">
                        <ul>
                            <?php
                            if (isset($categories) && !empty($categories)) {
                                foreach ($categories as $catslug => $catvalue) {
                                    echo '<li>' . $this->Html->link($catvalue, array('controller' => 'jobs', 'action' => 'listing', 'slug' => $catslug)) . '</li>';
                                }
                            } else {
                                ?>
                                <li><?php echo __d('home', 'No record found', true); ?></li>
                            <?php } ?>
                            
                        </ul>
                    </div>

                </div>




            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>