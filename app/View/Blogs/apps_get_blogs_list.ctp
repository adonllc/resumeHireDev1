<!DOCTYPE HTML>
<?php

//$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>            
            <?php
//            if (isset($title_for_layout))
//                echo $title_for_layout;
//            else
                echo $title_for_layout;
            ?>
        </title>

        <?php echo $this->Html->css('front/style.css'); ?>
        <?php echo $this->Html->css('front/media.css'); ?>
        <?php echo $this->Html->css('front/responsive.css'); ?>
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
        <!--[if IE 8]>
        <?php echo $this->Html->css('front/ie8.css'); ?>  
        <![endif]-->
        <!--[if IE 9]>
        <?php echo $this->Html->css('front/ie9.css'); ?>
        <![endif]-->
        <!-- Includes for this -->
        <!--[if IE]>
        <?php echo $this->Html->script('https://html5shiv.googlecode.com/svn/trunk/html5.js'); ?>
        <![endif]-->
        <!-- javascript -->



        <script type="text/javascript">
            <!--
            var BrowserDetect = {
                init: function() {
                    this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
                    this.version = this.searchVersion(navigator.userAgent)
                            || this.searchVersion(navigator.appVersion)
                            || "an unknown version";
                    this.OS = this.searchString(this.dataOS) || "an unknown OS";
                },
                searchString: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var dataString = data[i].string;
                        var dataProp = data[i].prop;
                        this.versionSearchString = data[i].versionSearch || data[i].identity;
                        if (dataString) {
                            if (dataString.indexOf(data[i].subString) != -1)
                                return data[i].identity;
                        }
                        else if (dataProp)
                            return data[i].identity;
                    }
                },
                searchVersion: function(dataString) {
                    var index = dataString.indexOf(this.versionSearchString);
                    if (index == -1)
                        return;
                    return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
                },
                dataBrowser: [
                    {
                        string: navigator.userAgent,
                        subString: "Chrome",
                        identity: "Chrome"
                    },
                    {string: navigator.userAgent,
                        subString: "OmniWeb",
                        versionSearch: "OmniWeb/",
                        identity: "OmniWeb"
                    },
                    {
                        string: navigator.vendor,
                        subString: "Apple",
                        identity: "Safari",
                        versionSearch: "Version"
                    },
                    {
                        prop: window.opera,
                        identity: "Opera",
                        versionSearch: "Version"
                    },
                    {
                        string: navigator.vendor,
                        subString: "iCab",
                        identity: "iCab"
                    },
                    {
                        string: navigator.vendor,
                        subString: "KDE",
                        identity: "Konqueror"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "Firefox",
                        identity: "Firefox"
                    },
                    {
                        string: navigator.vendor,
                        subString: "Camino",
                        identity: "Camino"
                    },
                    {// for newer Netscapes (6+)
                        string: navigator.userAgent,
                        subString: "Netscape",
                        identity: "Netscape"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "MSIE",
                        identity: "Explorer",
                        versionSearch: "MSIE"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "Gecko",
                        identity: "Mozilla",
                        versionSearch: "rv"
                    },
                    {// for older Netscapes (4-)
                        string: navigator.userAgent,
                        subString: "Mozilla",
                        identity: "Netscape",
                        versionSearch: "Mozilla"
                    }
                ],
                dataOS: [
                    {
                        string: navigator.platform,
                        subString: "Win",
                        identity: "Windows"
                    },
                    {
                        string: navigator.platform,
                        subString: "Mac",
                        identity: "Mac"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "iPhone",
                        identity: "iPhone/iPod"
                    },
                    {
                            string: navigator.platform,
                            subString: "Linux",
                            identity: "Linux"
                        }
                    ]

                };
                BrowserDetect.init();

                // -->
        </script>
        <script type="text/javascript">
            <!--
            function bodyLoad(){
                        //var jj = BrowserDetect.browser + ' ' + BrowserDetect.version + ' ' + BrowserDetect.OS ;
                        document.getElementsByTagName('body')[0].className += BrowserDetect.OS + ' ' + BrowserDetect.browser + ' v' + BrowserDetect.version;
                //alert( jj);
            }
            window.onload = bodyLoad;
                    // -->
        </script>
        <style>
        </style>
    </head>


    <body style="background:#fff;">
        <div  style=" padding:5px 10px;" >
           

            
<?php
if (isset($blogList) && !empty($blogList)) {


    foreach ($blogList as $list) {
        ?>
        <div class="blog_bx">
            <div class="blog_date"><i class="fa fa-calendar" aria-hidden="true"></i><span><?php echo date('F d, Y', strtotime($list['Blog']['created'])); ?></span></div>

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
                        <h4><a href="<?php echo HTTP_PATH.'/apps/blogs/getBlogsdetail/'.$list['Blog']['slug'] ?>"><?php echo $list['Blog']['title'] ?></a></h4>
                        <h4><?php // echo $this->Html->link($list['Blog']['title'], array('controller' => 'blogs', 'action' => 'detail', 'slug' => $list['Blog']['slug'], 'ext' => 'html')); ?></h4>

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


                    <div class="clr"></div>
                </div>
            </div>
        </div>


    <?php }
    ?>
   
    <?php
}
?>
        

        </div>
        <!-- end of #container -->
    </body>
</html>
