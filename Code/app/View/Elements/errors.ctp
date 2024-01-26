<?php 	if (!empty($errors) && $errors!="") {?>
<div class='ActionMsgBox1 error' id='msgID'>
<div class="errors">
    
    <ul>
        <?php foreach ($errors as $field => $error) { ?>
        <li><?php 
        if (is_array($error)){echo implode("<br/>",$error);}else{echo $error;}
         ?></li>
        <?php } ?>
    </ul>
</div>
</div>
<?php } ?>