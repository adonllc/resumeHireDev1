<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>
<tr>
    <td valign="top">
        <!-- Begin Middle Content -->
        <table width="100%">
            <tbody>
                <tr>
                    <td style="color:#98C11A; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo ucfirst($username); ?>,</td>
                </tr>
                <tr>
                    <td style="color:#434343; font-size:13px; line-height:18px;">
                        <p style="color:#434343; margin:10px 0 0;"><b style="color:#000000;">Welcome to</b> <?php echo SITE_URL;?> </p>
                        <p style="color:#434343; margin:10px 0 0;">We are pleased to announce that your Event Planner <?php echo $user_type;?> account has been created successfully.</p>
                        
                        <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;">Email Address:</strong> <?php echo $email; ?></p>
                        <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;">Password:</strong> <?php echo $password; ?></p>    
                         <?php if ($link) { ?>
                        <p style="color:#434343; margin:10px 0 0;">Please click the link below to activate your account. </p>
                        <p style="color:#434343; margin:10px 0 0;"><a style="text-decoration:underline;" href="<?php echo $link; ?>">Click Here</a> to activate your account. </p>                      
                        <?php } else { ?>
                        <p style="color:#434343; margin:10px 0 0;">Please click the below link to access site. </p>
                        <p style="color:#434343; margin:10px 0 0;"><a style="text-decoration:underline;" href="<?php echo HTTP_PATH; ?>">Click Here</a> </p>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#434343; font-size:13px; line-height:18px;">
                        <p style="color:#434343; margin:10px 0 0;">If you need any assistance, please e-mail us at <?php echo $site_link;?>.</p>

                        <p style="color:#98C11A;font:bold 15px Arial, Helvetica, sans-serif; margin:10px 0 0;"> The <?php echo $site_title;?> Team</p>
                    </td>
                </tr>
            </tbody></table>
        <!-- End Middle Content --> 
    </td>
</tr>