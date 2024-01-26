<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?> 
<tr>
    <td style="color:#0d4f87; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;">Your daily job report email notification on <?php echo $site_title;?>. </p>
        <p style="color:#434343; margin:10px 0 0;">
        <table style="color:#434343; font-size:13px;" >
            <tr>
                <td><strong>Job Title</strong></td>
                <td><strong>&nbsp;</strong></td>
                <td><strong>Number Of Jobseekers</strong></td>
            </tr>
            <?php
            $sendJobs = array_map('current', $sendJobs);
            foreach($sendJobs as $vall) {  
                $vallArray = explode('@@@', $vall);
                
                ?>
                <tr>
                    <td><?php echo $vallArray[0];?></td>
                    <td><strong>&nbsp;</strong></td>
                    <td style="text-align: center"><?php echo $vallArray[1];?></td>
                </tr>
            <?php } ?>
        </table>   
        </p>
   
        <p style="color:#434343; margin:10px 0 0;"><a style="text-decoration:underline;" href="<?php echo $link; ?>">Click Here</a> to login and view your job listing. </p>
        
    </td>
</tr>
<?php //exit; ?>