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

<?php if ($staticpages) { ?>
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
<div id='listID'>
        <?php
        //echo $this->Javascript->link('jquery-1.4.2.min');
        $urlArray = array_merge(array('controller' => 'pages', 'action' => 'index', $separator));
        $this->Paginator->_ajaxHelperClass = "Ajax";
        $this->Paginator->Ajax = $this->Ajax;
        $this->Paginator->options(array('update' => 'listID',
            'url' => $urlArray,
            'indicator' => 'loaderID'));

        ?>
        <?php echo $this->Form->create("Page", array("url" => "index", "method" => "Post")); ?>
    <div class="columns mrgih_tp">
        <div id="listingJS" style="display: none;" ></div>



        <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
            <div class="countrdm"><?php echo $this->Paginator->counter('No of Pages <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
            &nbsp;
            <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('Page')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Page')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
            </span>
        </div>
        <table class="table table-striped table-advance table-hover table-bordered">
            <thead>
                <tr>
                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Page.static_page_title', 'Title'); ?></th>
                    <th><i class=" fa fa-gavel"></i> Action</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    $adminLId = $this->Session->read('adminid');
                    $checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));
                    foreach ($staticpages as $page) { ?>
                <tr>
                    <td><span class="text-green"><?php echo $this->Html->link($page['Page']['static_page_title'], array('controller' => 'pages', 'action' => 'editPage', $page['Page']['static_page_heading']), array('class' => 'text-green')); ?></span></td>
                    <td>
                        <div id="loaderIDAct<?php echo $page['Page']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                        <span id="status<?php echo $page['Page']['id']; ?>">
                                    <?php
                                    if ($page['Page']['status'] == '1') {
                                        echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'pages', 'action' => 'deactivatepage', $page['Page']['id']), array('update' => 'status' . $page['Page']['id'], 'indicator' => 'loaderIDAct' . $page['Page']['id'], 'confirm' => 'Are you sure you want to Hide ?', 'escape' => false, 'title' => 'Hide'));
                                    } else {
                                        echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'pages', 'action' => 'activatepage', $page['Page']['id']), array('update' => 'status' . $page['Page']['id'], 'indicator' => 'loaderIDAct' . $page['Page']['id'], 'confirm' => 'Are you sure you want to Publish ?', 'escape' => false, 'title' => 'Publish'));
                                    }
                                    ?>
                        </span>
                                <?php 
                                if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 10, 2)){
                                    echo $this->Html->link('<i class="fa fa-pencil-square"></i>', array('controller' => 'pages', 'action' => 'editPage', $page['Page']['static_page_heading']), array('escape' => false, 'title' => 'Edit', 'class' => 'btn btn-warning btn-xs'));
                                }?>
                                <?php echo $this->Html->link('<i class="fa fa-info"></i>', '#info' . $page['Page']['id'], array('escape' => false, 'title' => 'View', 'class' => 'btn btn-primary btn-xs', 'rel' => 'facebox')); ?>

                    </td>
                </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
        <?php echo $this->Form->end(); ?>
    <div id="pagingLinks" align="right">
            <?php __("Showing Page"); ?>
        <div class="countrdm"><?php echo $this->Paginator->counter('No of Pages <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
        &nbsp;
        <span class="custom_link pagination"> 
                <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                <?php if ($this->Paginator->hasPrev('Page')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                <?php if ($this->Paginator->hasNext('Page')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
        </span>
    </div>
</div>
    <?php
} else {
    ?>
<div class="columns mrgih_tp">
    <table class="table table-striped table-advance table-hover table-bordered">
        <tr>
            <td><div id="noRcrdExist" class="norecext">There are no Pages to show.</div></td>
        </tr>
    </table>
</div>
<?php }
?>
<?php foreach ($staticpages as $page) { ?>

<div id="info<?php echo $page['Page']['id']; ?>" style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="nzwh">
                    <?php echo $page['Page']['static_page_title']; ?>
            </legend>
           <div class="drt">
                <?php echo $page['Page']['static_page_description']; ?>
           </div>
        </fieldset>
    </div>

</div>
<?php }
?>
