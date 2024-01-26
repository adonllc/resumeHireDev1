

<?php if ($advertisements) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'banneradvertisements', 'action' => 'index', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Banneradvertisement", array("url" => "index", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Banneradvertisement')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Banneradvertisement')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Banneradvertisement.title', 'Ad Title'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Banneradvertisement.url', 'URL'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Banneradvertisement.advertisement_place', 'Ad Place'); ?></th>
                                    <th class="sorting_paging">Image Adverts / Google Adverts</th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Banneradvertisement.created', 'Posted Date'); ?></th>
                                    <th><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($advertisements as $advertisement) { ?>
                                    <tr>
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $advertisement['Banneradvertisement']['id']; ?>" /></td>
                                        <td data-title="Email"><?php echo $advertisement['Banneradvertisement']['title']; ?></td>
                                        <td data-title="Email">
                                            <?php
                                            if (strpos($advertisement['Banneradvertisement']['url'], 'http') === false) {
                                                $url1 = 'https://' . $advertisement['Banneradvertisement']['url'];
                                            } else {
                                                $url1 = $advertisement['Banneradvertisement']['url'];
                                            }

                                            echo $advertisement['Banneradvertisement']['url'] ? $this->Html->link($advertisement['Banneradvertisement']['url'], $url1, array('escape' => false, 'target' => '_blank', 'style' => 'color: #676767;font-size: 12px;')) : 'N/A';
                                            ?>
                                        </td>
                                        <td data-title="Email">
                                            <?php
                                            if ($advertisement['Banneradvertisement']['advertisement_place'] == 'home_ad1') {
                                                $place = 'home_ad1';
                                            }else if($advertisement['Banneradvertisement']['advertisement_place'] == 'home_ad2'){
                                                $place = 'home_ad2';
                                            }
                                            
                                            echo $place;
                                            ?>
                                        </td>
                                        <td data-title="Email">
                                            <?php
                                    if ($advertisement['Banneradvertisement']['type'] == 1) {
                                        if ($advertisement['Banneradvertisement']['advertisement_place'] == 'Bottom') {
                                            echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_BANNER_AD_IMAGE_PATH . $advertisement['Banneradvertisement']['image'] . "&w=200&h=90&zc=3&q=100", array());
                                        } else {
                                            echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_BANNER_AD_IMAGE_PATH . $advertisement['Banneradvertisement']['image'] . "&w=150&h=150&zc=3&q=100", array());
                                        }
                                    } else if ($advertisement['Banneradvertisement']['type'] == 3) {
                                        echo htmlentities($advertisement['Banneradvertisement']['text']);
                                    } else {
                                        echo htmlentities($advertisement['Banneradvertisement']['code']);
                                    }
                                    ?>
                                        </td>

                                        <td data-title="Created"><?php echo date('d F Y', strtotime($advertisement['Banneradvertisement']['created'])); ?></td>
                                        <td data-title="Action">
                                            <div id="loaderIDAct<?php echo $advertisement['Banneradvertisement']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <span id="status<?php echo $advertisement['Banneradvertisement']['id']; ?>">
                                                <?php
                                                if ($advertisement['Banneradvertisement']['status'] == '1') {
                                                    echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'banneradvertisements', 'action' => 'deactivateBanneradvertisement', $advertisement['Banneradvertisement']['slug']), array('update' => 'status' . $advertisement['Banneradvertisement']['id'], 'indicator' => 'loaderIDAct' . $advertisement['Banneradvertisement']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                } else {
                                                    echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'banneradvertisements', 'action' => 'activateBanneradvertisement', $advertisement['Banneradvertisement']['slug']), array('update' => 'status' . $advertisement['Banneradvertisement']['id'], 'indicator' => 'loaderIDAct' . $advertisement['Banneradvertisement']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                }
                                                ?>
                                            </span>

                                            <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "banneradvertisements", "action" => 'editBanneradvertisement', $advertisement['Banneradvertisement']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit')); ?>
                                            <?php //echo $this->Html->link('<i class="fa fa-pencil"></i>', '#', array('escape' => false,'title' => 'Edit','class'=>"btn btn-warning btn-xs"));  ?>
                                            <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'banneradvertisements', 'action' => 'deleteBanneradvertisement', $advertisement['Banneradvertisement']['slug']), array('update' => 'deleted' . $advertisement['Banneradvertisement']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
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
                    <?php echo $this->Form->text('Banneradvertisement.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Banneradvertisement.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 

                </div>
            </div>
            <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Banneradvertisement')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Banneradvertisement')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->text('Banneradvertisement.bannerName', array('type' => 'hidden', 'value' => $searchKey));
            }
            if (isset($searchDateFrom) && $searchDateFrom != '') {
                echo $this->Form->hidden('Banneradvertisement.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
            }
            if (isset($searchDateTo) && $searchDateTo != '') {
                echo $this->Form->hidden('Banneradvertisement.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
            }
            ?>
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

