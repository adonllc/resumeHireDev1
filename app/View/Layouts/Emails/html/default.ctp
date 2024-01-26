<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
$enquiry_mail = $this->requestAction(array('controller' => 'App', 'action' => 'getMailConstant', 'enquiry_mail'));

$logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));

if (isset($logoImage) && !empty($logoImage)) {
    $logo = DISPLAY_THUMB_WEBSITE_LOGO_PATH . $logoImage;
} else {
    $logo = ' ';
}
?>

<table width="90%" align="center" style="table-layout:fixed; font-family:Arial, Helvetica, sans-serif; border:1px solid #537286; box-shadow: 0 0 5px #537286; padding:10px 15px; max-width:710px;">
    <tbody>
        <tr>
            <td valign="top" style=" background-color: #e6ebf7;">
                <!-- Begin Header -->
                <table width="100%">
                    <tbody><tr>
                            <td>
                                <a href="<?php echo HTTP_PATH; ?>">
                                    <img alt="<?php echo $site_title; ?>" src="<?php echo $logo; ?>"/>
                                </a>


                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Header -->
            </td>
        </tr>

        <tr>
            <td valign="top">
                <!-- Begin Middle Content -->
                <table width="100%">
                    <tbody>
                        <tr><td><?php echo $content_for_layout; ?></td></tr>
                        <tr>
                            <td style="color:#434343; font-size:13px; line-height:18px;">
                     
                                <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'If you need any assistance, please e-mail us at', true);?> <?php echo $enquiry_mail; ?></p>

                                <p style="color:#0d4f87;font:bold 15px Arial, Helvetica, sans-serif; margin:10px 0 0;"> <?php echo __d('user', 'The', true);?> <?php echo $site_title; ?> <?php echo __d('user', 'Team', true);?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Middle Content --> 
            </td>
        </tr>


        <tr>
            <td>
                <!-- Begin Footer Notifications -->
                <table width="100%" style="border-top:1px solid #ddd;">
                    <tbody><tr>
                            <td style="font-size:11px; line-height:18px;">
                                <p style="margin:10px 0 0;"><?php echo __d('user', 'Please note: Any emails automatically generated from', true);?> <?php echo $site_title; ?> <?php echo __d('user', 'may include one-click links. These links may allow you to access your account without entering your login details. As such, please do not forward these email to anyone.', true);?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Footer Notifications -->
            </td>
        </tr>


        <tr>
            <td valign="top">
                <!-- Begin Footer -->
                <table width="100%" style="border-top:1px solid #ddd; background-color:#e6ebf7; color: #0B0B0B;">
                    <tbody><tr>
                            <td style="font-size:12px;">
                                <p style="color:#0d4f87; width:100%;">&nbsp;<?php echo __d('user', 'Copyright', true);?> &copy; <?php echo date('Y'); ?> <?php echo $site_title; ?> <?php echo __d('user', 'All Rights Reserved.', true);?></p>
<!--                                <p style="color:#FFFFFF;margin:10px 0 10px;">&nbsp;<a href="<?php //echo HTTP_PATH; ?>/about-us" style="color:#FFFFFF;">About Us</a>&nbsp; | &nbsp;<a href="<?php //echo HTTP_PATH; ?>/terms-and-condition" style="color:#FFFFFF;">Terms & Conditions</a>&nbsp; | &nbsp;<a href="<?php //echo HTTP_PATH; ?>/privacy-policy" style="color:#FFFFFF;">Privacy Policy</a></p>-->

                                <p style="color:#0d4f87;margin:10px 0 10px;">&nbsp;<?php
                                    $about_us = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'about-us'));
                                    if ($about_us == 1) {
                                        echo '<a href="'. HTTP_PATH .'/about_us" style="color:#0d4f87;">'.__d('home', 'About Us', true).'</a>';
                                    }
                                    ?>&nbsp; | <?php
                                    $term_and_condition = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'terms-and-conditions'));
                                    if ($term_and_condition == 1) {
                                        
                                        echo '<a href="'. HTTP_PATH .'/terms-and-conditions.html" style="color:#0d4f87;">'.__d('home', 'Terms and Conditions', true).'</a>';
                                    }
                                    ?>&nbsp; | <?php
                                    $privacy_policy = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'privacy-policy'));
                                    if ($privacy_policy == 1) {
                                        echo '<a href="'. HTTP_PATH .'/privacy-policy.html" style="color:#0d4f87;">'.__d('home', 'Privacy Policy', true).'</a>';
                                    }
                                    ?></p>



                            </td>
                            <td align="right">
                                <a href="<?php echo HTTP_PATH; ?>">
                                    <img alt="<?php echo $site_title; ?>" src="<?php echo $logo; ?>"/>
                                </a>

                                &nbsp;&nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Footer -->
            </td>
        </tr>


    </tbody>
</table>



<?php // exit; ?>




