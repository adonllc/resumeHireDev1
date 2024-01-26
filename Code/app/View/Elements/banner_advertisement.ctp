 <?php
        $adDetails = $this->requestAction('/banneradvertisements/getBanneradvertisement/job_selection/1');
        if (!empty($adDetails)) {
            ?>
            <div class="middel_bottom">
                <?php foreach ($adDetails as $ad_listing) { ?>
                    <?php
                    if (strpos($ad_listing['Banneradvertisement']['url'], 'http') === false) {
                        $url1 = 'http://' . $ad_listing['Banneradvertisement']['url'];
                    } else {
                        $url1 = $ad_listing['Banneradvertisement']['url']; //$ad_listing['Banneradvertisement']['url'];
                    }
                    ?>
                    <?php
                    if ($ad_listing['Banneradvertisement']['type'] == 1) {
                        echo $this->Html->link($this->Html->image(DISPLAY_FULL_BANNER_AD_IMAGE_PATH . $ad_listing['Banneradvertisement']['image']), $url1, array('escape' => false,'rel'=>'nofollow', 'target' => '_blank'));
                    } elseif ($ad_listing['Banneradvertisement']['type'] == 2) {
                        echo $ad_listing['Banneradvertisement']['code'];
                    } else {
                        echo $this->Html->link($ad_listing['Banneradvertisement']['text'], $url1, array('escape' => false,'rel'=>'nofollow', 'target' => '_blank'));
                    }
                    ?>
                <?php } ?>
            </div>
        <?php } ?>