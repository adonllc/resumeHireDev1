<style>
    <!--
    .colr1{background-color: LavenderBlush !important;}
    -->
</style>
<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>
<script type="text/javascript">
    $(document).ready(function($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })
</script>
<style>
    /* NZ Web Hosting - www.nzwhost.com 
     * Fieldset Alternative Demo
    */
    .fieldset {
        border: solid 2px #ff0000;
        background: #3ca4ee;
        margin-top: 20px;
        position: relative;
    }

    .legend {
        border: solid 2px #ff0000;
        left: 0.5em;
        top: -0.6em;
        position: absolute;
        background: #A7BB5C;
        font-weight: bold;
        padding: 0 0.25em 0 0.25em;
    }

    .nzwh-wrapper .content {
        margin: 1em 0.5em 0.5em 0.5em;
    }

    legend.nzwh {
        background: none repeat scroll 0 0 #fff !important;
        border: 1px solid #a7a7a7 !important;
        border-radius: 5px !important;
        color: #a0a0a0;
        font-weight: normal;
        left: 0.5em;
        padding: 5px;
        position: absolute;
        top: -0.99em;
        width: auto !important;
    }

    fieldset.nzwh {
        background: none repeat scroll 0 0 #eee;
        border: 1px solid #a7a7a7;
        margin-top: 10px;
        padding: 0 10px;
        position: relative;
    }
</style>
<?php if ($certificates) { ?>
 
    <ul class="job_heading">
        <li>Sr. No.</li>
        <li>Certificate</li>
        <li>Uploaded on</li>
        <li>Action</li>
    </ul>
    <?php
    $srNo = 1;
    foreach ($certificates as $certificate) {
        ?>
        <ul class="job_list">
            <li><?php echo $srNo++; ?></li>
            <li class="jobdi">
                 <?php 
                    $profile_image = $certificate['Certificate']['document'];
                    $path = UPLOAD_CERTIFICATE_PATH . $profile_image;
                    if (file_exists($path) && !empty($profile_image)) {
                        echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_CERTIFICATE_PATH.$certificate['Certificate']['document']. "&w=100&zc=1&q=100", array('escape' => false,'rel'=>'nofollow'));
                    } else {
                        echo $this->Html->image('front/no_image_user.png');
                    }
                ?>
            </li>
            <li><?php echo date('F d,Y', strtotime($certificate['Certificate']['created'])); ?></li>
            <li>
                <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'candidates', 'action' => 'deleteCertificate', $certificate['Certificate']['slug']), array('class' => 'btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false,'rel'=>'nofollow', 'title' => 'Delete')); ?>
                                            <a href="#info<?php echo $certificate['Certificate']['id']; ?>" rel="facebox" title="View" class="btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                            <div id="loaderIDEmail<?php echo $certificate['Certificate']['id']; ?>" style="display:none;position:absolute;margin:-30px -1px 0px 124px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
            </li>
        </ul>
        <?php
    }
    ?>
    
<?php }else { ?>
    <div class="no_found">No record found.</div>
<?php } ?>

    

<?php foreach ($certificates as $certificate) { ?>

    <div id="info<?php echo $certificate['Certificate']['id']; ?>" style="display: none;">
       
                    <div class="ccddff">
                        <?php 
                         echo $this->Html->image(DISPLAY_CERTIFICATE_PATH.$certificate['Certificate']['document']);
                        ?>
                    </div>
             
        </div>

    </div>
<?php } ?>