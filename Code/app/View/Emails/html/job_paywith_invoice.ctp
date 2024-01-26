<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>  
<tr>
    <td style="color:#0d4f87; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;">Your <?php echo $site_title; ?>t invoice is attached below - Please see the payment advice for payment details.</p>
        <p style="color:#434343; margin:10px 0 0;">Thank you for choosing <?php echo $site_title;?>.</p>
        <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;">Job Title:</strong> <?php echo $jobTitle;?></p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Invoice Number:</strong> <?php echo $invoiceInumber;?></p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Date:</strong> <?php echo date('F d, Y');?></p> 
    </td>
</tr>





    <?php //exit;?>