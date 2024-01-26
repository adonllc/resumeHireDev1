<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?> 
<tr>
    <td style="color:#0d4f87; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;">Welcome to <?php echo $site_title; ?> </p>
        <p style="color:#434343; margin:10px 0 0;">We are pleased to announce that your employer account has been created successfully.</p>
        <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;">Company Name:</strong> <?php echo $company_name;?></p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Email Address:</strong> <?php echo $email;?></p>
<!--        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Password:</strong> <?php //echo $password;?></p>                  -->
        <p style="color:#434343; margin:10px 0 0;">Please click the link below to activate your account. </p>
        <p style="color:#434343; margin:0px 0 0;"><a style="text-decoration:underline;" href="<?php echo $link; ?>">Click Here</a> to activate your account. </p>
    </td>
</tr>

<?php //exit;?>