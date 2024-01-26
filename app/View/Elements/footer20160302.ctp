<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
$facebook_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','facebook_link'));
$instagram_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','instagram_link'));
$linkedin_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','linkedin_link'));
$enquiry_mail = $this->requestAction(array('controller' => 'App', 'action' => 'getMailConstant','enquiry_mail'));
$company_name = ClassRegistry::init('Setting')->field('company_name', array('Setting.id' => 1));

?> 

<footer>
    <div class="wrapper">
        <div class="wr_pad">
            <div class="cols_for">
                <div class="footer_box_ttile"><?php echo $site_title ?></div>
                <div class="clear"></div>

                <div class="footer_menu">
                    <ul>
                        <li>
                            <?php echo $this->Html->link('How it Works',array('controller'=>'homes','action'=>'index#howitworks'),array());?>
                        </li>
                        <li><?php echo $this->Html->link('Media','/media',array());?></li>
                        <li><?php echo $this->Html->link('Contact us','/contact-us',array());?></li>
                    </ul>
                </div>
            </div>

            <div class="cols_for">
                <div class="footer_box_ttile">Questions? Need help?</div>
                <div class="clear"></div>

                <div class="footer_menu">
                    <ul>
                        <li><span>E-mail us : </span> <a href="mailto:<?php echo $enquiry_mail;?>"> <?php echo ' '.$enquiry_mail;?></a></li>
                        <li><span>ABN : </span> <span>15 162 159 408</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>

        <div class="footer_bottom">
            <div class="foo_logo"></div>
            <div class="foot_center">
                &copy; 2016 <?php echo $company_name; ?> &nbsp;|&nbsp; <?php echo $this->Html->link('FAQ','/faq',array());?> &nbsp;|&nbsp; <?php echo $this->Html->link('Terms','/terms-and-condition',array());?> &nbsp;|&nbsp; <?php echo $this->Html->link('Privacy Policy','/privacy-policy',array());?> <!--&nbsp;|&nbsp; <span style="display:none;"><a href="https://www.logicspice.com/" target="_blank" class="comp">Web Design Company</a> LogicSpice</span>-->
            </div>
            <div class="fot_social">
                <ul>
                    <li><a href="<?php echo $facebook_link; ?>" target="_new"><img src="<?php echo HTTP_IMAGE;?>/front/fb_icon.png" alt="img"  /></a></li>
                    <li><a href="<?php echo $instagram_link; ?>" target="_new"><img src="<?php echo HTTP_IMAGE;?>/front/instagram_icon.png" alt="img" /></a></li>
                    <li><a href="<?php echo $linkedin_link; ?>" target="_new"><img src="<?php echo HTTP_IMAGE;?>/front/in_icon.png" alt="img" /></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>