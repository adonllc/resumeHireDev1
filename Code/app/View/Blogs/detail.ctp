<div class="iner_pages_formate_box">
    <div class="wrapper">

        <div class="iner_form_bg_box">
            <?php if (isset($blogData) && !empty($blogData)) { ?>
                <div class="top_page_name_title">
                    <div class="page_name_boox">
                        <span style="float: left;"><h1><?php echo $blogData['Blog']['title']; ?></h1></span>
                        <!-- for back button-->
                        <span style="float: right;"><?php echo $this->Html->link('', $refer, array('class' => 'back_navy fa fa-reply', 'title' => 'Back', 'rel' => 'nofollow')); ?></span>

                    </div>
                </div>
                <div class="clear"></div>

                <div class="inpfil inpfil_new">

                    <?php if (!empty($blogData['Blog']['description'])) { ?>
                        <div class="blog_tit blog_tit_new">
                            <span class="detail_imgset">
                                <?php
                                $path = UPLOAD_THUMB_BLOG_PATH . $blogData['Blog']['image'];
                                if (file_exists($path) && !empty($blogData['Blog']['image'])) {
                                    ?>

                                    <?php
                                    echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_BLOG_PATH . $blogData['Blog']['image'] . "&w=500&zc=1&q=100", array('escape' => false, 'alt' => $blogData['Blog']['image']));
                                } else {
                                    echo $this->Html->image('front/no_image_user.png', array('escape' => false, 'alt' => 'no_image_user'));
                                }
                                ?>

                            </span>
                            <div class="detailpage_sdsv">
                                <span class="det_datessd"><?php echo date('F d, Y', strtotime($blogData['Blog']['created'])); ?></span>
                                <p style="color: #666;"><?php echo $blogData['Blog']['description']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="clr"></div>
            </div>
        <?php } ?>
    </div>

</div>
</div>
