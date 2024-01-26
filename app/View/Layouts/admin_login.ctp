<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Mosaddek">
        <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

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

        <!-- Bootstrap core CSS -->
        <?php
        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('bootstrap-reset.css');
        ?>    
        <!--external css-->
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />      
        <!-- Custom styles for this template -->
        <?php
        echo $this->Html->css('style.css');
        echo $this->Html->css('style-responsive.css');
        ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
        <?php
        echo $this->Html->script('html5shiv.js');
        echo $this->Html->script('respond.min.js');
        ?>
        <![endif]-->
    </head>

    <body class="login-body <?php
        if (isset($forgot_hidden) && $forgot_hidden == 0) {
            echo 'modal-open';
        }
        ?>" style="<?php
          if (isset($forgot_hidden) && $forgot_hidden == 0) {
              echo 'padding-right: 13px;';
          }
        ?>">
        <!-- js placed at the end of the document so the pages load faster -->
        <?php
        echo $this->Html->script('jquery.js');
        echo $this->Html->script('bootstrap.min.js');
        echo $this->Html->script('jquery.validate.min.js');
        ?> 
        <!--script for this page-->
        <?php //echo $this->Html->script('form-validation-script.js');   ?>
        <?php echo $content_for_layout; ?>      

        <script type="text/javascript">
            window.onload = function () {
                setTimeout("hideSessionMessage()", 8000);
            };
            function hideSessionMessage() {
                $('#msgID').fadeOut("slow");
            }
        </script>
        

    </body>
</html>

<!-- Localized -->