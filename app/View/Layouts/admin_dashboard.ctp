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
        <meta name="author" content="">
        <meta name="keyword" content="">
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


        <title>Administration - <?php
if (isset($title_for_layout))
    echo $title_for_layout;
else
    echo $site_title;
// - Modification de #Username
?>
        </title>
        <!-- Bootstrap core CSS -->
        <?php
        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('bootstrap-reset.css');
		echo $this->Html->css('bootstrap.css');
        ?>
        <!--external css-->
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />      
        <!--right slidebar-->
        <?php echo $this->Html->css('slidebars.css'); ?>
        <!-- Custom styles for this template -->
        <?php
        echo $this->Html->css('style.css');
        echo $this->Html->css('style-responsive.css');
        ?>    
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/data-tables/DT_bootstrap.css" />
        <?php echo $this->Html->css('bootstrapValidator.min.css'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-fileupload/bootstrap-fileupload.css" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
        <?php echo $this->Html->script('html5shiv.js'); ?>
        <?php echo $this->Html->script('respond.min.js'); ?>
        <![endif]-->




        <?php 
        
        echo $this->Html->script('jquery.js');
        ?>
    </head>

    <body>
        <style type="text/css">
            .form-horizontal .has-feedback .form-control-feedback {
                top: 10px !important;
                right: 15px;
            }

            .groupform .form-control-feedback {
                top: 0;
                right: -30px;
            }
            .site-footer {
                background: #5B6E84;
                color: white;
                padding: 10px 0;
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 999;
            }

        </style>

        <section id="container" class="">
            <!--header start-->
            <?php echo $this->element('admin/header'); ?>
            <!--header end-->

            <!--sidebar start-->
            <?php echo $this->element('admin_menu'); ?>
            <!--sidebar end-->
            <!--main content start-->
            <?php echo $content_for_layout; ?>
            <!-- Right Slidebar start -->
            <!-- Right Slidebar end -->
            <!--footer start-->
            <footer class="site-footer">
                <div class="text-center">
                    <?php echo date('Y'); ?> &copy; <?php echo $site_title; ?>.
                     <div class="powered_by_inner">
        <div class="powered_tital_inner">Powered by: </div>
        <div class="powered_logo_inner"><a href="https://www.logicspice.com/" target="_blank">Logicspice</a></div>
    </div>
                    <a href="#" class="go-top">
                        <i class="fa fa-angle-up"></i>
                    </a>
                </div>
            </footer>
            <!--footer end-->
        </section>


        <!--main content end-->
        <!-- Js writeBuffer -->
        <?php
        if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
            echo $this->Js->writeBuffer();
        // Writes cached scripts
        ?>
        <?php //echo $this->element('sql_dump'); ?>  
        <!-- js placed at the end of the document so the pages load faster -->
        <?php
        echo $this->Html->script('bootstrap.min.js');
        echo $this->Html->script('jquery.dcjqaccordion.2.7.js', array('class' => 'include'));
        echo $this->Html->script('jquery.scrollTo.min.js');
        echo $this->Html->script('jquery.nicescroll.js');
        echo $this->Html->script('jquery.sparkline.js');
        ?>
        <script src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
        <?php
        echo $this->Html->script('owl.carousel.js');
        echo $this->Html->script('jquery.customSelect.min.js');
        echo $this->Html->script('respond.min.js');
        //right slidebar
        echo $this->Html->script('slidebars.min.js');
        //common script for all pages
        echo $this->Html->script('common-scripts.js');
        echo $this->Html->script('sparkline-chart.js');
        echo $this->Html->script('easy-pie-chart.js');
        echo $this->Html->script('jquery-ui-1.9.2.custom.min.js');
        ?>
        <script>

            //owl carousel

            $(document).ready(function () {
                $("#owl-demo").owlCarousel({
                    navigation: true,
                    slideSpeed: 300,
                    paginationSpeed: 400,
                    singleItem: true,
                    autoPlay: true

                });
            });

            //custom select box

            $(function () {
                $('select.styled').customSelect();
            });

        </script>
             

    </body>
<?php echo $this->element('sql_dump'); ?>
</html>
