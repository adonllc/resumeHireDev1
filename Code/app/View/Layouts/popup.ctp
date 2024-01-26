<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>

<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="no-js ie6" dir="ltr" lang="en-US"><![endif]-->
<!--[if IE 7 ]><html class="no-js ie7" dir="ltr" lang="en-US"><![endif]-->
<!--[if IE 8 ]><html class="no-js ie8" dir="ltr" lang="en-US"><![endif]-->
<!--[if IE 9 ]><html class="no-js ie9" dir="ltr" lang="en-US"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <!--  <link rel="shortcut icon" type="image/x-icon" href="<?php //echo HTTP_PATH; ?>/app/webroot/img/front/favicon.ico"> -->

            <?php   

            $favImage = classRegistry::init('Admin')->field('Admin.favicon', array('id' => 1));

              if(isset($favImage) && !empty($favImage)){
                  $fav = DISPLAY_FULL_FAV_PATH. $favImage;
              }else{
                  $fav = ' ';
              }

               echo '<link rel="shortcut icon" type="image/x-icon" href="'.$fav.'">'; 
             // echo $this->Html->image($fav, array('rel' => 'shortcut icon','type'=>'image/x-icon'));
          ?>  
         <title>
            <?php
            if (isset($title_for_layout))
                echo $title_for_layout;
            else
                echo $site_title;
            ?>
        </title>
        <?php echo $this->Html->css('front/style.css'); ?>
        <!--[if IE 8]>
        <?php echo $this->Html->css('front/ie8.css'); ?>
        <![endif]-->
        <!--[if IE 9]>
        <?php echo $this->Html->css('front/ie9.css'); ?>
        <![endif]-->
        <!-- Includes for this -->
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- javascript -->
    </head>
    <body>
        <div class="all_di">
            <?php echo $content_for_layout ?>
        </div>
        <div class="clr" style="clear:both;"></div>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>