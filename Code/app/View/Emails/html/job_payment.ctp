<tr>
    <td style="color:#0d4f87; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;">Dear</b> <?php echo $username;?>,</td>
</tr>
<tr>
    <td style="color:#434343; font-size:13px; line-height:18px;">
        <p style="color:#434343; margin:10px 0 0;">Your job post payment for job: <b><?php echo $jobTitle;?></b> was confirmed by PayPal with below information.</p>
        <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;">Job Title:</strong> <?php echo $jobTitle;?></p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Transaction #:</strong> <?php echo $transactionId;?></p>
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Amount:</strong> <?php echo CURRENCY.$amountPaid;?></p>   
        <p style="color:#434343; margin:0px 0 0;"><strong style="width:150px;">Date:</strong> <?php echo date('F d, Y');?></p> 
        <p style="color:#434343; margin:10px 0 0;"><a style="text-decoration:underline;" href="<?php echo $link; ?>">Click here</a> to view your job listing. </p>
    </td>
</tr>