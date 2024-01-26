<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
?>
<tr>
    <td style="color:#0d4f87; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;">Here's your weekly email notifications on <?php echo $site_title;?>. </p>
        <p style="color:#434343; margin:0px 0 10px;">Please click the view detail links for more details. </p>
    </td>
</tr>
<tr>    
    <td>
    	<table width="100%" style="font-size:13px;" cellpadding="0" cellspacing="0">
        	<tr>
            	<td style="border-bottom:solid 2px #ddd; padding:4px 0;"><b>Job Title</b></td>
                <td style="border-bottom:solid 2px #ddd; padding:4px 0;"><b>Location</b></td>
                <td width="15%" style="border-bottom:solid 2px #ddd; padding:4px 0;"><b>Live Link</b></td>              
            </tr>
            <?php
               foreach($userJobs as $job){ ?>
                    <tr>
                        <td style="border-bottom:solid 1px #ddd; padding:4px 0;"><?php echo $job['Job']['title'];?></td>
                        <td style="border-bottom:solid 1px #ddd; padding:4px 0;"><?php echo $job['City']['city_name'].', '.$job['State']['state_name'].', '.$job['Job']['postal_code'];?></td>
                        <td style="border-bottom:solid 1px #ddd; padding:4px 0;"><a href="<?php echo HTTP_PATH;?>/jobs/detail/<?php echo $job['Job']['slug'];?>" style="text-decoration:none; color:#3c719e;"><b>View Detail</b></a></td>            
                    </tr>
               <?php }  
            ?>  
           
        </table>
    </td>
</tr>
<?php //exit; ?>