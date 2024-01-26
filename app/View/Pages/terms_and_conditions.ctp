<!DOCTYPE HTML>
<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
//pr($data); die;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>            
            <?php
            if (isset($title_for_layout))
                echo $title_for_layout;
            else
                echo $site_title;
            ?>
        </title>

        <?php echo $this->Html->css('front/style.css'); ?>
        <?php echo $this->Html->css('front/responsive.css'); ?>
          <!--  <link rel="shortcut icon" type="image/x-icon" href="<?php //echo HTTP_PATH;     ?>/app/webroot/img/front/favicon.ico"> -->

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
                init: function () {
                    this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
                    this.version = this.searchVersion(navigator.userAgent)
                            || this.searchVersion(navigator.appVersion)
                            || "an unknown version";
                    this.OS = this.searchString(this.dataOS) || "an unknown OS";
                },
                searchString: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        var dataString = data[i].string;
                        var dataProp = data[i].prop;
                        this.versionSearchString = data[i].versionSearch || data[i].identity;
                        if (dataString) {
                            if (dataString.indexOf(data[i].subString) != -1)
                                return data[i].identity;
                        } else if (dataProp)
                            return data[i].identity;
                    }
                },
                searchVersion: function (dataString) {
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
            function bodyLoad() {
                //var jj = BrowserDetect.browser + ' ' + BrowserDetect.version + ' ' + BrowserDetect.OS ;
                document.getElementsByTagName('body')[0].className += BrowserDetect.OS + ' ' + BrowserDetect.browser + ' v' + BrowserDetect.version;
                //alert( jj);
            }
            window.onload = bodyLoad;
            // -->
        </script>

    </head>


    <body style="background:#fff;">
        <div  style=" padding:0" >
            <div class="logod" style=" text-align:center; margin-bottom:10px; ">
                 <?php
                

                        $logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));
                        // pr($logoImage);
                        if (isset($logoImage) && !empty($logoImage)) {
                            $logo = DISPLAY_FULL_WEBSITE_LOGO_PATH . $logoImage;
                        } else {
                            $logo = ' ';
                        }
                        echo $this->Html->image($logo);
                    //   echo $this->Html->link($this->Html->image($logo, array('alt' => $site_title, 'title' => $site_title)), '/', array('escape' => false, 'rel' => 'nofollow', 'class' => ''));
                        ?>
                <?php  ?></div>

            <div class="iner_pages_formate_box">
                <div class="wrapper">
                    <div class="iner_form_bg_box">
                        <div class="top_page_name_box">
                            <div class="page_name_boox"><span>
                                <?php 
                                    $static_page_title = 'static_page_title';
                                    $static_page_description = 'static_page_description';
                                    if($_SESSION['Config']['language'] !='en'){
                                        $static_page_title = 'static_page_title_'.$_SESSION['Config']['language'];
                                        $static_page_description = 'static_page_description_'.$_SESSION['Config']['language'];
                                    }
                                    echo $pageContent['Page'][$static_page_title]; ?>
                                </span></div>
                        </div>
                        <div class="clear"></div>

                        <div class="inpfil">
                            <?php echo $pageContent['Page'][$static_page_description]; ?>
                            <div class="clr"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end of #container -->
        <style>
            body{ margin: 0}
            .logod{ background: #f1f1f1; padding: 10px;}
			.logod img {
	width: 200px;
}
        </style>
    </body>
</html>
