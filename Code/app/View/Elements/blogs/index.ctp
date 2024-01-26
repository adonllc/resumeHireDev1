
<?php global $monthName;
if (isset($blogList) && !empty($blogList)) {


    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => array('controller' => 'blogs', 'action' => 'index', $separator),
        'indicator' => 'loaderID'));


    foreach ($blogList as $list) {
        ?>
        <div class="blog_bx">
            <div class="blog_date"><i class="fa fa-calendar" aria-hidden="true"></i><span><?php echo $monthName[date('n', strtotime($list['Blog']['created']))].' '.date('d, Y', strtotime($list['Blog']['created'])); ?></span></div>

            <div class="blog_txts">

                <?php if (!empty($list['Blog']['description'])) { ?>

                    <div class="blog_im">
                        <?php
                        $path = UPLOAD_THUMB_BLOG_PATH . $list['Blog']['image'];
                        if (file_exists($path) && !empty($list['Blog']['image'])) {
                            echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_THUMB_BLOG_PATH . $list['Blog']['image'] . "&w=200&zc=1&q=100", array('escape' => false, 'alt' => $list['Blog']['image']));
                        } else {
                            echo $this->Html->image('front/no_image_user.png', array('escape' => false, 'alt' => 'no_image_user'));
                        }
                        ?>



                    </div>
                <?php } ?>
                <div class="blog_rights">
                    <div class="blog_titsl">
                        <h4><?php echo $this->Html->link($list['Blog']['title'], array('controller' => 'blogs', 'action' => 'detail', 'slug' => $list['Blog']['slug'], 'ext' => 'html')); ?></h4>

                    </div>
                    <div class="blog_desdr"><?php //echo $list['Blog']['description'];              ?>
                        <?php
                        //echo $this->Text->truncate($job['Job']['description'], 150, array('html' => true));
                        if (str_word_count($list['Blog']['description']) < 10) {
                            echo $list['Blog']['description'];
                        } elseif (str_word_count($list['Blog']['description']) > 10 && str_word_count($list['Blog']['description']) < 50) {
                            $pos = strpos($list['Blog']['description'], ' ', 50);
                            echo substr($list['Blog']['description'], 0, $pos) . '...';
                        } elseif (str_word_count($list['Blog']['description']) > 51 && str_word_count($list['Blog']['description']) < 100) {
                            $pos = strpos($list['Blog']['description'], ' ', 100);
                            echo substr($list['Blog']['description'], 0, $pos) . '...';
                        } elseif (str_word_count($list['Blog']['description']) > 101 && str_word_count($list['Blog']['description']) < 120) {
                            $pos = strpos($list['Blog']['description'], ' ', 100);
                            echo substr($list['Blog']['description'], 0, $pos) . '...';
                        } elseif (str_word_count($list['Blog']['description']) > 121 && str_word_count($list['Blog']['description']) < 150) {
                            $pos = strpos($list['Blog']['description'], ' ', 120);
                            echo substr($list['Blog']['description'], 0, $pos) . '...';
                        } else {
                            $pos = strpos($list['Blog']['description'], ' ', 400);
                            echo substr($list['Blog']['description'], 0, $pos) . '...';
                        }
                        ?>
                    </div>
                    <div class="blog_tit"> <b> <?php echo $this->Html->link(__d('user', 'Read More', true).'...', array('controller' => 'blogs', 'action' => 'detail', $list['Blog']['slug'])); ?></b></div>


                    <div class="clr"></div>
                </div>
            </div>
        </div>


    <?php }
    ?>
    <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>'.__d('user', 'No. of Records', true).' </span><span class="">{:start}</span><span> - </span><span class="">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="">{:count}</span>'
            );
            ?> 
        </div>

        <div class="paginations bloglist">
            <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));   ?> 
            <?php if ($this->Paginator->hasPrev('Blog')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('Blog')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false, 'rel' => 'nofollow')); ?> 
            <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow'));   ?> 

        </div>	
    </div>
    <?php
}
?>
        