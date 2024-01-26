<?php if(isset($KeywordList) && !empty($KeywordList) && $keyword){ ?>
<ul id="keyword-list">
<?php
foreach($KeywordList as $Keyword) { 
?>
    <li onClick="selectKeyword('<?php echo ($Keyword); ?>','<?php echo $ids; ?>','<?php echo $suggesstion; ?>');"><?php echo ($Keyword); ?></li>
<?php } ?>
</ul>
<?php } ?>