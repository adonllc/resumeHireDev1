<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
//pr($data); die;
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
$site_tagline = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'tagline'));
$site_url = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'url'));


//$userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $this->Session->read('user_id'))));
?>
<html xmlns="https://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="">
                <meta property="og:locale" content="en_US" />
                <meta property="og:type" content="website" />
                <meta property="og:title" content="<?php echo $site_title; ?> - <?php echo $site_tagline; ?>" />
                <meta property="og:description" content="" />
                <meta property="og:url" content="<?php echo $site_url; ?>" />
                <meta property="og:site_name" content="<?php echo $site_url; ?>" />
                <meta property="fb:app_id" content="966242223397117" />
                <meta property="og:image" content="<?php echo HTTP_PATH; ?>/app/webroot/img/front/Social_Media_Card1.jpg" />
                   <!--  <link rel="shortcut icon" type="image/x-icon" href="<?php //echo HTTP_PATH;                     ?>/app/webroot/img/front/favicon.ico"> -->

                <!--conical for seo purpose-->
<!--                <link rel="canonical" href="<?php //echo HTTP_PATH;    ?>"/>-->
<link rel="canonical" href="<?php echo HTTP_PATH; ?>" />
                <?php
                if (isset($catData) && !empty($catData)) {
                    if (isset($catData['Category']['meta_title']) && !empty($catData['Category']['meta_title'])) {

                        echo '<meta name="title" content=" ' . $catData['Category']['meta_title'] . ' " />';
                    } else {
                        $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
                        if (isset($metaData['Admin']['meta_catetitle'])) {
                            echo '<meta name="title" content=" ' . $metaData['Admin']['meta_catetitle'] . ' " />';
                        }
                    }

                    if (isset($catData['Category']['meta_keywords']) && !empty($catData['Category']['meta_keywords'])) {
                        echo '<meta name="keywords" content=" ' . $catData['Category']['meta_keywords'] . ' " />';
                    } else {
                        $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
                        if (isset($metaData) && !empty($metaData)) {
                            if (isset($metaData['Admin']['meta_catekeywords'])) {
                                echo '<meta name="keywords" content=" ' . $metaData['Admin']['meta_catekeywords'] . ' " />';
                            }
                        }
                    }

                    if (isset($catData['Category']['meta_description']) && !empty($catData['Category']['meta_description'])) {
                        echo '<meta name="description" content=" ' . $catData['Category']['meta_description'] . ' " />';
                    } else {
                        $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
                        if (isset($metaData) && !empty($metaData)) {
                            if (isset($metaData['Admin']['meta_catedescription'])) {
                                echo '<meta name="description" content=" ' . $metaData['Admin']['meta_catedescription'] . ' " />';
                            }
                        }
                    }
                } else if (isset($metaData) && !empty($metaData)) {
                    // $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
                    //pr($metaData);
                    if (isset($metaData['Admin']['meta_jobtitle'])) {
                        echo '<meta name="title" content=" ' . $metaData['Admin']['meta_jobtitle'] . ' " />';
                    }

                    if (isset($metaData['Admin']['meta_jobkeywords'])) {
                        echo '<meta name="keywords" content=" ' . $metaData['Admin']['meta_jobkeywords'] . ' " />';
                    }

                    if (isset($metaData['Admin']['meta_jobdescription'])) {
                        echo '<meta name="description" content=" ' . $metaData['Admin']['meta_jobdescription'] . ' " />';
                    }

                    if (isset($metaData['Admin']['meta_catetitle'])) {
                        echo '<meta name="title" content=" ' . $metaData['Admin']['meta_catetitle'] . ' " />';
                    }

                    if (isset($metaData['Admin']['meta_catekeywords'])) {
                        echo '<meta name="keywords" content=" ' . $metaData['Admin']['meta_catekeywords'] . ' " />';
                    }

                    if (isset($metaData['Admin']['meta_catedescription'])) {
                        echo '<meta name="description" content=" ' . $metaData['Admin']['meta_catedescription'] . ' " />';
                    }
                } elseif (isset($blogData) && !empty($blogData)) {

                    if (isset($blogData['Blog']['meta_title'])) {
                        echo '<meta name="title" content=" ' . $blogData['Blog']['meta_title'] . ' " />';
                    }

                    if (isset($blogData['Blog']['meta_keyword'])) {
                        echo '<meta name="keywords" content=" ' . $blogData['Blog']['meta_keyword'] . ' " />';
                    }

                    if (isset($blogData['Blog']['meta_description'])) {
                        echo '<meta name="description" content=" ' . $blogData['Blog']['meta_description'] . ' " />';
                    }
                } else {
                    //echo '';
                    $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
                    //pr($metaData);
                    if (isset($metaData['Admin']['default_title'])) {
                        echo '<meta name="title" content=" ' . $metaData['Admin']['default_title'] . ' " />';
                    }

                    if (isset($metaData['Admin']['default_keyword'])) {
                        echo '<meta name="keywords" content=" ' . $metaData['Admin']['default_keyword'] . ' " />';
                    }

                    if (isset($metaData['Admin']['default_description'])) {
                        echo '<meta name="description" content=" ' . $metaData['Admin']['default_description'] . ' " />';
                    }
                }
                ?>

                <?php
                $favImage = classRegistry::init('Admin')->field('Admin.favicon', array('id' => 1));

                if (isset($favImage) && !empty($favImage)) {
                    $fav = DISPLAY_FULL_FAV_PATH . $favImage;
                } else {
                    $fav = ' ';
                }

                echo '<link rel="shortcut icon" type="image/x-icon" href="' . $fav . '">';
                // echo $this->Html->image($fav, array('rel' => 'shortcut icon','type'=>'image/x-icon'));
                ?>    


                <title>
                    <?php
                    if (isset($catData) && !empty($catData)) {
                        if (isset($catData['Category']['meta_title']) && !empty($catData['Category']['meta_title'])) {
                            echo $catData['Category']['meta_title'];
                        } else {
                            $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
                            if (isset($metaData['Admin']['meta_jobtitle'])) {
                                echo $metaData['Admin']['meta_jobtitle'];
                            }
                        }
                    } else if (isset($blogData) && !empty($blogData)) {
                        if (isset($blogData['Blog']['meta_title'])) {
                            echo $blogData['Blog']['meta_title'];
                        }
                    } else if (isset($jobdetails) && isset($jobdetails)) {
                        $catTitle = classRegistry::init('Category')->find('first', array('conditions' => array('Category.name' => $jobdetails['Category']['name'])));
                        echo $jobdetails['Job']['title'] . "," . $catTitle['Category']['meta_title'];
                    } else {
                        if (isset($title_for_layout))
                            echo $title_for_layout;
                        else
                            echo $site_title;
                    }
                    ?>
                </title>
                <?php echo $this->Html->css('front/bootstrap.css'); ?>
               
                 <?php echo $this->Html->css('front/owl.theme.default.min.css'); ?> 
                <?php echo $this->Html->css('front/owl.carousel.min.css'); ?> 
                <?php echo $this->Html->css('front/aos.css'); ?> 
                 <?php //echo $this->Html->css('front/home.css'); ?>
                 
                <?php
                if ($_SESSION['Config']['language'] == 'en') {
                    echo $this->Html->css('front/home.css?v=4');
                    echo $this->Html->css('front/style.css?v=4');
                    echo $this->Html->css('front/media.css?v=4');
                } else {
                    $lng = $_SESSION['Config']['language'];
                    echo $this->Html->css("front/home_$lng.css?v=4");
                    echo $this->Html->css("front/style_$lng.css?v=4");
                    echo $this->Html->css("front/media_$lng.css?v=4");
                }
                ?> 
                
                <?php echo $this->Html->css('front/font-awesome.css'); ?> 
                <?php echo $this->Html->css('front/responsive_home.css'); ?>
                <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/> 
                <?php echo $this->Html->script('front/jquery.min.js'); ?> 
                <?php echo $this->Html->script('front/bootstrap.js'); ?> 
                <?php echo $this->Html->script('front/owl.carousel.js'); ?> 
                <?php echo $this->Html->script('front/aos.js'); ?> 
                <?php echo $this->Html->script('front/typing.min.js'); ?> 
                <script type="text/javascript">
                    $(document).ready(function () {
                        <?php if (!isset($_SESSION['never']) && ((!isset($_SESSION['countryName']) || $_SESSION['countryName'] == "") || (!isset($_SESSION['regionName']) || $_SESSION['regionName'] == ""))) { ?>
                            setTimeout(function () {
                                var html = '<div id="geoLocPopUp" class="userPrompt animate"><div><p class="caption"><?php echo __d('home', 'Share your location with us for more relevant jobs', true); ?></p><p class="desc"><?php echo __d('home', 'You can turn them off anytime from browser settings', true); ?></p></div><span id="block" onclick="notNow()" class="fr geoLocBtn later"><?php echo __d('home', 'Later', true); ?></span><span id="allow" onclick="yesnow()" class="fr geoLocBtn sure"><?php echo __d('home', 'Sure', true); ?></span></div>'
                                $(html).appendTo('body');
                            }, 1000);
                        <?php } ?>


                        $(".dev_menu").click(function () {
                            $("nav").toggle();
                        });
                    });

                    function notNow() {
                        $('#geoLocPopUp').remove();
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo HTTP_PATH; ?>/users/never/",
                            cache: false,
                            data: {},
                            beforeSend: function () {
                                $('#geoLocPopUp').remove();
                            },
                            complete: function () {
                                $('#geoLocPopUp').remove();
                            },
                            success: function (result) {
                                $('#geoLocPopUp').remove();
                            }
                        });
                    }
                    function yesnow() {
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo HTTP_PATH; ?>/users/setLocationInSession/",
                            cache: false,
                            data: {},
                            beforeSend: function () {
                                $('#geoLocPopUp').remove();
                            },
                            complete: function () {
                                $('#geoLocPopUp').remove();
                            },
                            success: function (result) {
                                if (result == 0) {
                                    alert("We cannot able to track you location for now, please try again later");
                                    $('#geoLocPopUp').remove();
                                } else {
                                    $('#geoLocPopUp').remove();
                                }

                            }
                        });

                    }
                </script>
                </head>
                <body>
                    <div id="page_full">
                        <?php echo $this->element('header'); ?>
                        <?php echo $content_for_layout; ?>
                        <?php echo $this->element('footer'); ?>
                    </div>
                </body>
                </html>

                <?php
                //echo $this->element('sql_dump'); ?>
<script>
  AOS.init({
                duration: 1200, once: true
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