<!DOCTYPE HTML>

<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>
<html>
    <head>
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo HTTP_PATH; ?>/app/webroot/img/front/favicon.png">
        <title>  
                <?php
                if (isset($title_for_layout))
                    echo $title_for_layout;
                else
                    echo $site_title;
                ?>
        </title>
        <?php 
            if($_SESSION['Config']['language'] =='en'){
                echo $this->Html->css('front/home.css');   
                echo $this->Html->css('front/style.css');   
                echo $this->Html->css('front/media.css');
            }else{
                $lng = $_SESSION['Config']['language'];
                echo $this->Html->css("front/home_$lng.css?v=4");   
                echo $this->Html->css("front/style_$lng.css?v=4");   
                echo $this->Html->css("front/media_$lng.css?v=4");
            }                    
        ?> 

        <?php echo $this->Html->script('front/1.7.1.jquery.min.js'); ?>
        <?php echo $this->Html->script('front/cssua.min.js'); ?>
        <?php echo $this->Html->script('front/main.js'); ?>
        <?php echo $this->Html->script('front/html5.js'); ?>     
        <?php echo $this->Html->script('front/jquery.html5-placeholder-shim.js'); ?>
        <?php echo $this->Html->script('front/jquery.styleSelect.js'); ?>

        <!--[if lt IE 7 ]><html class="no-js ie6" dir="ltr" lang="en-US"><![endif]-->
        <!--[if IE 7 ]><html class="no-js ie7" dir="ltr" lang="en-US"><![endif]-->
        <!--[if IE 8 ]><html class="no-js ie8" dir="ltr" lang="en-US"><![endif]-->
        <!--[if IE 9 ]><html class="no-js ie9" dir="ltr" lang="en-US"><![endif]-->
        <!--[if (gte IE 9)|!(IE)]><!-->
        <!--<![endif]-->
        <script>
            if (/*@cc_on!@*/false) {
                document.documentElement.className += ' ie10';
            }
        </script><!--<![endif]--> 
        <?php echo $this->Html->script('front/responsive_slides.js'); ?>
        <?php echo $this->Html->script('front/browser-detect.js'); ?>     
    </head>
    <body class="inner_pg">
        <div class="mid_prt">
             <?php echo $this->Html->image('front/bg.jpg',array('alt'=>'','class'=>'bg'));?>
            <div class="atodf">
                <?php echo $this->element('header');?>
                <?php echo $content_for_layout; ?>
            </div>
            <div class="clr"></div>
             <?php echo $this->element('footer');?>             
        </div>
        <?php //echo $this->element('sql_dump'); ?>
        <script>
            $("#bSlides").responsiveSlides({
                auto: true, // Boolean: Animate automatically, true or false
                speed: 600, // Integer: Speed of the transition, in milliseconds
                timeout: 4200, // Integer: Time between slide transitions, in milliseconds
                pager: false, // Boolean: Show pager, true or false
                nav: true, // Boolean: Show navigation, true or false
                random: false, // Boolean: Randomize the order of the slides, true or false
                pause: false, // Boolean: Pause on hover, true or false
                pauseControls: false, // Boolean: Pause when hovering controls, true or false
                prevText: "", // String: Text for the "previous" button
                nextText: "", // String: Text for the "next" button
                maxwidth: "", // Integer: Max-width of the slideshow, in pixels
                navContainer: "", // Selector: Where controls should be appended to, default is after the 'ul'
                manualControls: "", // Selector: Declare custom pager navigation
                namespace: "rslides", // String: Change the default namespace used
                before: function () {}, // Function: Before callback
                after: function () {}     // Function: After callback
            });
        </script>
        <script type="text/javascript">
            $('.success_msg').append('<div class="close" title="<?php echo __d('users_session_msg', 'Remove', true); ?>"></div>');
            $('.error_msg').append('<div class="close" title="<?php echo __d('users_session_msg', 'Remove', true); ?>"></div>');
            $('.success_msg .close').click(function () {
                $('.success_msg').fadeOut();
            });
            $('.error_msg .close').click(function () {
                $('.error_msg').fadeOut();
            });
        </script>
        <script>
                    <?php 
                        $data = ClassRegistry::init('Changecolors')->find('first',array('conditions'=>array('Changecolors.id'=>6))); 
                        // print_r($data);exit;
                        ?>
                        var bgcolor = '<?php echo $data['Changecolors']['theme_background'];  ?>';
                        var themecolor = '<?php echo $data['Changecolors']['theme_color'];  ?>';
                        // document.body.style.setProperty("--header-background", bgcolor);
                        document.documentElement.style.setProperty('--header-background', bgcolor);
                        document.documentElement.style.setProperty('--card-background', themecolor);
                        document.documentElement.style.setProperty('--theme-hover', bgcolor);
                       
                </script>
    </body>
</html>
