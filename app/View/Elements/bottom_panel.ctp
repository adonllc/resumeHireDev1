<?php
    $adDetail = $this->requestAction('/banneradvertisements/getBanneradvertisement/Bottom/1');
    if (!empty($adDetail)) {
        foreach ($adDetail as $ad_list) {
            ?>
            <?php
            if (strpos($ad_list['Banneradvertisement']['url'], 'http') === false) {
                $url1 = 'http://' . $ad_list['Banneradvertisement']['url'];
            } else {
                $url1 = $ad_list['Banneradvertisement']['url']; //$ad_list['Banneradvertisement']['url'];
            }
            ?>
            <?php
            if ($ad_list['Banneradvertisement']['type'] == 1) {
                list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_BANNER_AD_IMAGE_PATH.$ad_list['Banneradvertisement']['image']);
                if($width == 728 && $height == 90) {
                    echo $this->Html->link($this->Html->image(DISPLAY_FULL_BANNER_AD_IMAGE_PATH.$ad_list['Banneradvertisement']['image']), $url1, array('escape' => false,'rel'=>'nofollow', 'target' => '_blank'));
                }else{
                    echo $this->Html->link($this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_BANNER_AD_IMAGE_PATH.$ad_list['Banneradvertisement']['image']. "&w=1149&h=90&zc=3&q=100"), $url1, array('escape' => false,'rel'=>'nofollow', 'target' => '_blank'));                                
                }
            } elseif ($ad_list['Banneradvertisement']['type'] == 2) {
                echo $ad_list['Banneradvertisement']['code'];
            } else {
                echo $this->Html->link($ad_list['Banneradvertisement']['text'], $url1, array('escape' => false, 'target' => '_blank'));
            }
            ?>
            <?php
        }
    }
?>