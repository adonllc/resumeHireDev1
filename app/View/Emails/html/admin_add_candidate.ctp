<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));

?> 
<tr>
    <td style="color:#98C11A; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;">Welcome to <?php echo $site_title; ?> </p>
        <p style="color:#434343; margin:10px 0 0;">We are pleased to announce that your jobseeker account has been created by admin.</p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Email Address:</strong> <?php echo $email;?></p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Password:</strong> <?php echo $password;?></p>                  
    </td>
</tr>





