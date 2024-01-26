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




<?php if ($mailsettings) { ?>
<div class="col-lg-12">
    <section class="panel">
        <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
        <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'settings', 'action' => 'manageMails', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Setting", array("url" => "manageMails", "method" => "Post")); ?>
        <div class="columns mrgih_tp">
               <?php echo $this->Session->flash(); ?>
            
            <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('MailSetting')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('MailSetting')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                </span>
            </div>
            <div class="panel-body">
                <section id="no-more-tables">
                    <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                <th class="sorting_paging"><?php echo $this->Paginator->sort('MailSetting.mail_name', 'Email Name'); ?></th>
                                <th class="sorting_paging"><?php echo $this->Paginator->sort('MailSetting.mail_value', 'Email Address'); ?></th>
                                <th><i class=" fa fa-gavel"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                              //  global $designation;
                                foreach ($mailsettings as $mail) {
                                    ?>
                            <tr>
                                <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $mail['MailSetting']['id']; ?>" /></td>
                                <td data-title="Mail value"><?php echo $mail['MailSetting']['mail_name'] ? $mail['MailSetting']['mail_name'] : 'N/A'; ?></td>
                                <td data-title="Mail value"><?php echo $mail['MailSetting']['mail_value'] ? $mail['MailSetting']['mail_value'] : 'N/A'; ?></td>
                                <td data-title="Action">
                                    <div id="loaderIDAct<?php echo $mail['MailSetting']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                    <span id="status<?php echo $mail['MailSetting']['id']; ?>">
                                               
                                    </span>

                                            <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "settings", "action" => 'editMails', $mail['MailSetting']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit')); ?>
                                    <a href="#info<?php echo $mail['MailSetting']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                    <div id="loaderIDEmail<?php echo $mail['MailSetting']['id']; ?>" style="display:none;position:absolute;margin:-30px -1px 0px 124px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>

                                </td>
                            </tr>
    <?php } ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
        <div id="actdiv" class="outside">
            <div class="block-footer mogi">
                    <?php echo $this->Form->text('MailSetting.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('MailSetting.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
            </div>
        </div>
        <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
            <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
            &nbsp;
            <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('MailSetting')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('MailSetting')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
            </span>
        </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('MailSetting.keyword', array('type' => 'hidden', 'value' => $searchKey));
            }
    
            echo $this->Form->end(); ?>
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

<?php   foreach ($mailsettings as $mails) { ?>

<div id="info<?php echo $mails['MailSetting']['id']; ?>"
     style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="nzwh">  Email Details </legend>
            <div class="drt">
               
                <span>Email Name :</span>  <?php echo $mails['MailSetting']['mail_name']; ?><br/>
                <span>Email Address :</span>  <?php echo $mails['MailSetting']['mail_value']; ?><br/>
            </div>
        </fieldset>
    </div>

</div>
<?php }
?>