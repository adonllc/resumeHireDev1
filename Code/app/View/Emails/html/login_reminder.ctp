<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?> 
<tr>
    <td style="color:#98C11A; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;"><?php echo $site_title; ?> account has been temporarily deactivated for 2 minutes, if you are trying more than 6 times then your account will be blocked for 30 minutes.</p>
    </td>
</tr>