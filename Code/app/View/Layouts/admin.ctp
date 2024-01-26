<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
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
      <!--  <link rel="shortcut icon" type="image/x-icon" href="<?php //echo HTTP_PATH;  ?>/app/webroot/img/front/favicon.ico"> -->

        <?php
        $favImage = classRegistry::init('Admin')->field('Admin.favicon', array('id' => 1));

        if (isset($favImage) && !empty($favImage)) {
            $fav = DISPLAY_FULL_FAV_PATH . $favImage;
        } else {
            $fav = ' ';
        }

        echo '<link rel="shortcut icon" type="image/x-icon" href="' . $fav . '">';
        //echo $this->Html->image($fav, array('rel' => 'shortcut icon','type'=>'image/x-icon'));
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
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" />      
        
        <!--right slidebar-->
<?php echo $this->Html->css('slidebars.css'); ?>
        <!-- Custom styles for this template -->
        <?php
        echo $this->Html->css('style.css');
        echo $this->Html->css('style-responsive.css');
        echo $this->Html->css('table-responsive.css');
        ?>    
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
        <link href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/data-tables/DT_bootstrap.css" />
<?php echo $this->Html->css('bootstrapValidator.min.css'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
     <link rel="stylesheet" type="text/css" href="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/spectrum-1.8.1/spectrum.css">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
<?php echo $this->Html->script('html5shiv.js'); ?>
        <![endif]-->        
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
        </style>

        <section id="container" class="">


            <!--header start-->
<?php echo $this->element('admin/header'); ?>
            <!--header end-->


            <!--sidebar start-->
<?php echo $this->element('admin_menu'); ?>
            <!--sidebar end-->


            <!-- js placed at the end of the document so the pages load faster -->
<?php
echo $this->Html->script('listing.js');
echo $this->Html->script('jquery.js');
echo $this->Html->script('bootstrap.min.js');
echo $this->Html->script('jquery.validate.js');
echo $this->Html->script('jquery.scrollTo.min.js');
echo $this->Html->script('jquery.nicescroll.js');
echo $this->Html->script('jquery-ui-1.9.2.custom.min.js');
echo $this->Html->script('jquery.dcjqaccordion.2.7.js', array('class' => 'include'));
?>

            <!--custom switch-->
<?php echo $this->Html->script('bootstrap-switch.js'); ?>
            <!--custom tagsinput-->
            <?php echo $this->Html->script('jquery.tagsinput.js'); ?>
            <!--custom checkbox & radio-->
            <?php echo $this->Html->script('ga.js'); ?>

            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-daterangepicker/date.js"></script>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
            <!--<script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/ckeditor/ckeditor.js"></script>-->
<?php echo $this->Html->script('bootstrapValidator.js'); ?>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
            <?php //echo $this->Html->script('advanced-form-components.js'); ?>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
            <?php echo $this->Html->script('respond.min.js'); ?>
          <!-- <script type="text/javascript" src="<?php //echo HTTP_PATH;    ?>/app/webroot/js/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> -->
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
            <!--right slidebar-->
<?php echo $this->Html->script('slidebars.min.js'); ?>

            <!--common script for all pages-->
<?php echo $this->Html->script('common-scripts.js'); ?>

            <!--script for this page-->
<?php echo $this->Html->script('form-component.js'); ?>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<?php //echo $this->Html->script('advanced-form-components.js');  ?>
            <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/fuelux/js/spinner.min.js"></script>
             <script type="text/javascript" src="<?php echo HTTP_PATH; ?>/app/webroot/js/assets/spectrum-1.8.1/spectrum.js"></script>
           

            <!--main content start-->
<?php echo $content_for_layout; ?>

            <!--main content end-->



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

        <!-- Js writeBuffer -->
<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
// Writes cached scripts
?>
        <?php //echo $this->element('sql_dump'); ?> 

        <script type="text/javascript">
            window.onload = function () {
                setTimeout("hideSessionMessage()", 8000);
                initialize();
            };
            function hideSessionMessage() {
                $('#msgID').fadeOut("slow");
            }
        </script> 


        <script>

            $(document).ready(function () {
                $('#useraddedit').bootstrapValidator({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh'
                    },
                    fields: {
                        civility: {
                            validators: {
                                notEmpty: {
                                    message: 'The gender is required'
                                }
                            }
                        },
                        companyname: {
                            message: 'The ExpertName ExpertName is not valid',
                            validators: {
                                notEmpty: {
                                    message: 'The ExpertName ExpertName is required and cannot be empty'
                                },
                                stringLength: {
                                    min: 6,
                                    max: 30,
                                    message: 'The ExpertName ExpertName must be more than 6 and less than 30 characters long'
                                },
                                regexp: {
                                    regexp: /^[a-zA-Z0-9_]+$/,
                                    message: 'The ExpertName ExpertName can only consist of alphabetical, number and underscore'
                                }
                            }
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'The email is required and cannot be empty'
                                },
                                emailAddress: {
                                    message: 'The input is not a valid email address'
                                }
                            }
                        }
                    }
                });
            });

        </script>

        <script type="text/javascript">
            $('#companydescription').wysihtml5();
//            $(document).on("keyup", ".keyword-box", function (e) {
    $(".keyword-box").keyup(function(e){
                e.preventDefault();
                $(".common-serach-box").hide();
                var suggesstion = $(this).data('suggesstion');
                var search = $(this).data('search');
                var ids = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "<?php echo HTTP_PATH ?>/keywords/ajaxkeywordlist",
                    data: 'keyword=' + $(this).val() + '&suggesstion=' + suggesstion + '&search=' + search + '&ids=' + ids,
                    dataType: "html",
                    beforeSend: function () {
//			$(this).css("background"," url(img/loading.gif) no-repeat 125px");
                    },
                    success: function (data) {
                        $("#" + suggesstion).show();
                        $("#" + suggesstion).html(data);
                        $(this).css("background", "none");
                    }
                });
            });
            $(document).on("keyup", ".specialty-box", function (e) {
//    $(".specialty-box").keyup(function(e){
                e.preventDefault();
                $(".common-serach-box").hide();
                var suggesstion = $(this).data('suggesstion');
                var search = $(this).data('search');
                var ids = $(this).attr('id');
                var graduation = $("#" + $(this).data('graduation')).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo HTTP_PATH ?>/keywords/ajaxspecialtylist",
                    data: 'keyword=' + $(this).val() + '&suggesstion=' + suggesstion + '&search=' + search + '&ids=' + ids + '&graduation=' + graduation,
                    dataType: "html",
                    beforeSend: function () {
//			$(this).css("background"," url(img/loading.gif) no-repeat 125px");
                    },
                    success: function (data) {
                        $("#" + suggesstion).show();
                        $("#" + suggesstion).html(data);
                        $(this).css("background", "none");
                    }
                });
            });




            function selectKeyword(val, ids, suggesstion) {
                $("#" + ids).val(val);
                $("#" + suggesstion).hide();
            }
        </script>
        

    </body>
</html>

