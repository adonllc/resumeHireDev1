<div class="middels_bx">
    <div class="wrapper">
        <div class="iner_form_bg_box">
            <?php
            if ($this->Session->check('error_msg')) {
                echo "<div class='error_msg error_lo'><span class='span_text'>" . $this->Session->read('error_msg') . "</span></div>";
                $this->Session->delete("error_msg");
            }
            if ($this->Session->check('success_msg')) {
                echo "<div class='success_msg success_lo'><span class='span_text'>" . $this->Session->read('success_msg') . "</span></div>";
                $this->Session->delete("success_msg");
            }
            ?>

            <div class="cebter">
                <div class="top_page_name_box">
                    <div class="page_name_boox"><h1><?php echo __d('home', 'Sitemap', true) ?></h1></div>
                </div>
                <div class="clear"></div>
                <div class="inpfil">
                <div class="edit_boxes edit_boxesdre">

                    <div class="ster_ti"><?php echo __d('home', 'Main Pages', true) ?></div>
                    <div class="link_menu link_menu_sitemap">
                        <ul>
                            <li><a href="<?php echo HTTP_PATH; ?>"><?php echo __d('user', 'Home', true) ?></a></li>
                            <li><a href="<?php echo HTTP_PATH . '/users/register/jobseeker' ?>"><?php echo __d('home', 'Jobseeker Register', true) ?></a></li>
                            <li><a href="<?php echo HTTP_PATH . '/users/register/employer' ?>"><?php echo __d('home', 'Employer Register', true) ?></a></li>
                            <li><a href="<?php echo HTTP_PATH . '/blog' ?>"><?php echo __d('home', 'Blog', true) ?></a></li>
                            <li><a href="<?php echo HTTP_PATH . '/contact-us' ?>"><?php echo __d('home', 'Contact us', true) ?></a></li>
                            <?php foreach ($urlArray as $key => $val) { 
                                echo '<li>' . $this->Html->link($val, array('controller' => 'pages', 'action' => 'staticpage', $key), array('rel' => 'nofollow')) . '</li>';
                            } ?>
                        </ul>
                    </div>

                </div>

                <div class="sitemap_bx25 catss">
                    <div class="ster_ti"><?php echo __d('home', 'Categories', true) ?></div>
                    <div class="link_menu link_menu_sitemap link_menu_sitemap_cated">
                        <ul>
                            <?php foreach ($categories as $category) { ?>
                                <li>
                                    <?php echo $this->Html->link($this->Text->Truncate(ucfirst($category['Category']['name']), 50), array('controller' => 'jobs', 'action' => 'listing', 'slug' => $category['Category']['slug']), array('escape' => false)); ?>
                                   
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="sitemap_bx25 catss">
                    <div class="ster_ti"><?php echo __d('home', 'Latest Jobs', true) ?></div>
                    <div class="link_menu link_menu_sitemap link_menu_sitemap_three">
                        <ul>
                            <?php
                            foreach ($jobs as $job) {
                                $cat = ClassRegistry::init('Category')->find('first', array('conditions' => array('Category.id' => $job['Job']['category_id'], 'Category.status' => 1)));
                                ?>
                                <li>
                                    <?php echo $this->Html->link($this->Text->Truncate(ucfirst($job['Job']['title']), 50), array('controller' => 'jobs', 'action' => 'detail', 'cat' => $cat['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html'), array('escape' => false)); ?>
                                   
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                </div>


            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>