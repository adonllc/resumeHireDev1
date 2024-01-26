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
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
          
            <?php echo $this->Form->create("User", array("url" => "index", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th class="sorting_paging">Sr. No.</th>
                                    <th class="sorting_paging">Certificate</th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> Uploaded on</th>
                                    <th><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $dd = 1;
                                foreach ($certificates as $certificate) {?>
                                    <tr>
                                        <td data-title="Certificate"><?php echo $dd++; ?></td>
                                        <td data-title="Certificate">
                                            <?php 
                                                $profile_image = $certificate['Certificate']['document'];
                                                $path = UPLOAD_CERTIFICATE_PATH . $profile_image;
                                                if (file_exists($path) && !empty($profile_image)) {
                                                    echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_CERTIFICATE_PATH.$certificate['Certificate']['document']. "&w=100&zc=1&q=100", array('escape' => false));
                                                } else {
                                                    echo $this->Html->image('front/no_image_user.png');
                                                }
                                            ?>
                                            
                                        </td>
                                        
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($certificate['Certificate']['created'])); ?></td>
                                        <td data-title="Action">
                                            <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'candidates', 'action' => 'deleteCertificate', $certificate['Certificate']['slug'],$cslug), array('class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
                                            <a href="#info<?php echo $certificate['Certificate']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                            <div id="loaderIDEmail<?php echo $certificate['Certificate']['id']; ?>" style="display:none;position:absolute;margin:-30px -1px 0px 124px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
           
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">No Record Found.</div></td>
            </tr>
        </table>
    </div>
    <?php }
?>



<?php foreach ($certificates as $certificate) { ?>

    <div id="info<?php echo $certificate['Certificate']['id']; ?>"
         style="display: none;">
        <!-- Fieldset -->
        <div class="nzwh-wrapper">
            <fieldset class="nzwh">
                
                <div class="drt">
                    <span></span> 
                    <div class="ccddff">
                        <?php 
                         echo $this->Html->image(DISPLAY_CERTIFICATE_PATH.$certificate['Certificate']['document']);
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>