<style>
    <!--
    .colr1{background-color: LavenderBlush !important;}
    -->
</style>
<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })
</script>

<?php

$constant = $this->requestAction(array('controller' => 'App', 'action' => 'getConstantData'));
//pr($constant); die;
?>
<?php if ($staticemailtemplates) {
    ?>
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px">
        <?php echo $this->Html->image("loader_large_blue.gif"); ?>
</div>
<div id='listID'>
    <?php
    //echo $this->Javascript->link('jquery-1.4.2.min');
    $urlArray = array_merge(array('controller' => 'emailtemplates', 'action' => 'index', $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => $urlArray,
        'indicator' => 'loaderID'));

    ?>
        <?php echo $this->Form->create("Emailtemplate", array("url" => "index", "method" => "Post")); ?>
    <div class="columns mrgih_tp">
        <div id="listingJS" style="display: none;" ></div>



        <div id="pagingLinks" align="right">
    <?php __("Showing Page"); ?>
            <div class="countrdm"><?php echo $this->Paginator->counter('No. of Email Templates <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
            &nbsp;
            <span class="custom_link pagination"> 
    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
    <?php if ($this->Paginator->hasPrev('Emailtemplate')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Emailtemplate')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
            </span>
        </div>
        <table class="table table-striped table-advance table-hover table-bordered">
            <thead>
                <tr>
                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Emailtemplate.title', 'Title'); ?></th>
                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Emailtemplate.subject', 'Subject'); ?></th>
                    <th><i class=" fa fa-gavel"></i> Action</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    $adminLId = $this->Session->read('adminid');
    $checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));
    foreach ($staticemailtemplates as $emailtemplate) { ?>         
                <tr>
                    <td class="bl">
                        <?php
                        //if ($this->Session->read('admin_type') == 'Admin') {
                            echo $this->Html->link($emailtemplate['Emailtemplate']['title'], array('controller' => 'emailtemplates', 'action' => 'editEmailtemplate' . '/' . $emailtemplate['Emailtemplate']['static_email_heading']), array('class' => ''));
                        //} else {
                            //echo $emailtemplate['Emailtemplate']['title'];
                        //}
                        ?>
                    </td>
                    <td class="bl">
                                <?php
                          
                                    echo $this->Html->link($emailtemplate['Emailtemplate']['subject'], array('controller' => 'emailtemplates', 'action' => 'editEmailtemplate' . '/' . $emailtemplate['Emailtemplate']['static_email_heading']), array('class' => ''));
                               
                                ?>
                    </td>

                    <td>
                                <?php
                              if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 9, 2)){
                                    echo $this->Html->link('<i class="fa fa-pencil-square"></i>', array('controller' => 'emailtemplates', 'action' => 'editEmailtemplate', $emailtemplate['Emailtemplate']['static_email_heading']), array('escape' => false, 'title' => 'Edit', 'class' => 'btn btn-warning btn-xs'));
                              }
                                ?>
                            <?php echo $this->Html->link('<i class="fa fa-info"></i>', '#info' . $emailtemplate['Emailtemplate']['id'], array('escape' => false, 'title' => 'View', 'class' => 'btn btn-primary btn-xs', 'rel' => 'facebox')); ?>
                    </td>


                </tr>

    <?php } ?>  

            </tbody>
        </table>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
    <?php
} else {
    ?>
<div class="columns mrgih_tp">
    <table class="table table-striped table-advance table-hover table-bordered">
        <tr>
            <td><div id="noRcrdExist" class="norecext">There are no Emailtemplate to show.</div></td>
        </tr>
    </table>
</div>
<?php
}

foreach ($staticemailtemplates as $page) {

    $toRepArray = array('[!HTTP_PATH!]', '[!SITE_TITLE!]');
    $fromRepArray = array(HTTP_PATH, $constant['SITE_TITLE']);


    $tofoot = array('[!HTTP_PATH!]', '[!SITE_TITLE!]', '["CURRENT_YEAR"]', '[!MAIL_FROM!]');
    $fromfoot = array(HTTP_PATH, $constant['SITE_TITLE'], date('Y'), $constant['from']);
    ?>

<div id="info<?php echo $page['Emailtemplate']['id']; ?>" style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="nzwh">
                <?php echo $page['Emailtemplate']['title']; ?>
            </legend>
           <div class="drt">

                <?php //echo str_replace($toRepArray, $fromRepArray, $page['Emailtemplate']['header']); ?>
                <?php echo $page['Emailtemplate']['template']; ?>
                <?php //echo str_replace($tofoot, $fromfoot, $page['Emailtemplate']['footer']); ?>
           </div>
        </fieldset>
    </div>

</div>
<?php }
?>









