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
<table width="710" align="center" style="table-layout:fixed; font-family:Arial, Helvetica, sans-serif; border:0px solid #537286; box-shadow: 0 0 5px #537286; padding:1px 0px;">
    <tbody>
        <?php /* ?><tr>
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
          </tr><?php */ ?>


        <tr> 
            <td>
                <?php echo $content_for_layout; ?>
            </td>
        </tr> 

        <tr>
            <td>
                <!-- Begin Footer Notifications -->
                <table width="100%" style="border-top:1px solid #ddd;">
                    <tbody><tr>
                            <td style="font-size:11px; line-height:18px;">
                                <p style="margin:10px 0 0;">You are receiving this email because you are a registered member or Newsletter subscriber member of the <a href="<?php echo HTTP_PATH; ?>" ><?php echo HTTP_PATH; ?></a></p>
                                <p style="color:#000; width:100%; padding: 0px 0 0 0;">To unsubscribe from our future  Newsletters, please <a style="color:#000;" href="<?php echo HTTP_PATH; ?>/newsletters/unsubscribe/<?php echo $email; ?>/<?php echo md5($id); ?>">click here</a> to unsubscribe.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Footer Notifications -->
            </td>
        </tr>


        <?php /* ?> <tr>
          <td valign="top">
          <!-- Begin Footer -->
          <table width="100%" style="border-top:1px solid #ddd; background-color:#e6ebf7; color: #0B0B0B;">
          <tbody><tr>
          <td style="font-size:12px;">
          <p style="color:#0d4f87; width:100%;">&nbsp;Copyright &copy; <?php echo date('Y'); ?> <?php echo $site_title; ?> All Rights Reserved.</p>
          <!--                                <p style="color:#FFFFFF;margin:10px 0 10px;">&nbsp;<a href="<?php //echo HTTP_PATH; ?>/about-us" style="color:#FFFFFF;">About Us</a>&nbsp; | &nbsp;<a href="<?php //echo HTTP_PATH; ?>/terms-and-condition" style="color:#FFFFFF;">Terms & Conditions</a>&nbsp; | &nbsp;<a href="<?php //echo HTTP_PATH; ?>/privacy-policy" style="color:#FFFFFF;">Privacy Policy</a></p>-->

          <p style="color:#0d4f87;margin:10px 0 10px;">&nbsp;<?php
          $about_us = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'about-us'));
          if ($about_us == 1) {
          echo '<a href="'. HTTP_PATH .'/about-us.html" style="color:#0d4f87;">About Us</a>';
          }
          ?>&nbsp; |<?php
          $term_and_condition = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'terms-and-conditions'));
          if ($term_and_condition == 1) {

          echo '<a href="'. HTTP_PATH .'/terms-and-conditions.html" style="color:#0d4f87;">Terms and Conditions</a>';
          }
          ?>&nbsp; |<?php
          $privacy_policy = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'privacy-policy'));
          if ($privacy_policy == 1) {
          echo '<a href="'. HTTP_PATH .'/privacy-policy.html" style="color:#0d4f87;">Privacy Policy</a>';
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
          </tbody></table>
          <!-- End Footer -->
          </td>
          </tr><?php */ ?>


<!--        <tr>
    <td valign="top">
         Begin Footer 
        <table width="92%" style="border-top:1px solid #ddd; background-color:#ebebeb; color: #000; text-align: center; margin: 0  0 0 26px">
            <tbody>
                <tr>
                    <td style="font-size:12px;">
                        <p style="color:#000; width:100%; padding: 5px 0 0 0;">You are receiving this email because you are a registered member or Newsletter subscriber member of the <a href="<?php //echo HTTP_PATH; ?>" >www.upliftjobs.com</a></p>
                        <p style="color:#000; width:100%; padding: 0px 0 0 0;">To unsubscribe from our future  Newsletters, please <a style="color:#000;" href="<?php //echo HTTP_PATH;  ?>/newsletters/unsubscribe/<?php //echo $email; ?>/<?php //echo md5($id); ?>">click here</a> to unsubscribe.</p>
                    </td>
                </tr>
            </tbody></table>
       
    </td>
</tr>-->
    </tbody>
</table>
<?php //exit; ?>








