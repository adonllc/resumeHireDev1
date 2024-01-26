
<style>
    .total_count{color: green; font-weight: bolder; }
    <!--
    .colr1{background-color: LavenderBlush !important;}
    .icon-user-3{  background-color: red;
                   color: #f1f1f1;
                   font-size: 42px !important;
                   margin-top: 5px;}
    -->
</style>

<?php if ($advertisements) { ?>

    <div id="listingJS" style="display: none;" ></div>
    <div id="loaderID" style="display:none;position:absolute;margin-left:400px;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <div id='listID'>
        <?php
        $this->Paginator->_ajaxHelperClass = "Ajax";
        $this->Paginator->Ajax = $this->Ajax;
        $this->Paginator->options(array('update' => 'listID',
            'url' => array('controller' => 'banneradvertisements', 'action' => 'index', $separator),
            'indicator' => 'loaderID'));
        ?>
        <?php echo $this->Form->create("Banneradvertisement", array("url" => "index", "method" => "Post")); ?>

        <div class="grid simple vertical">
            <div class="grid-title no-border">
                <h4><span class="">List Banner Advertisements</span></h4>
            </div>
            <div class="grid-body no-border" id="videowatermark_div">
                <div class="clr"></div>
                <table class="table no-more-tables">
                    <thead>
                        <tr>
                            <th style="width:5%">
                    <div class="">
                        <input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" />
                        <label for="checkall"></label>
                    </div>
                    </th>
                    <th class="cini" align="left">Serial No</th>
                    <th><?php echo $this->Paginator->sort('title', 'Ad Title'); ?></th>
                    <th><?php echo $this->Paginator->sort('url', 'URL'); ?></th>
                    <th><?php echo $this->Paginator->sort('advertisement_place', 'Ad Place'); ?></th>
                    <th>mage Adverts / Google Adverts</th>
                    <th><?php echo $this->Paginator->sort('created', 'Posted Date'); ?></th>
                    <th style="width:20%">Action</th>


                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $class = '';
                        $srNo = 1;
                        foreach ($advertisements as $advertisement) {
                            if ($i % 2 == 0) {
                                $class = 'colr1';
                            } else {
                                $class = '';
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td class="v-a-middle">
                                    <div class="">
                                        <input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $advertisement['Banneradvertisement']['id']; ?>" />
                                        <label for="checkbox0"></label>
                                    </div>
                                </td>
                                <td class="v-a-middle cini" align="left"><span class="muted"><?php echo $srNo; ?></span></td>
                                <td class="v-a-middle"><?php echo $advertisement['Banneradvertisement']['title']; ?></td>
                                <td class="v-a-middle">
                                    <?php
                                    if (strpos($advertisement['Banneradvertisement']['url'], 'http') === false) {
                                        $url1 = 'http://' . $advertisement['Banneradvertisement']['url'];
                                    } else {
                                        $url1 = $advertisement['Banneradvertisement']['url'];
                                    }

                                    echo $advertisement['Banneradvertisement']['url'] ? $this->Html->link($advertisement['Banneradvertisement']['url'], $url1, array('escape' => false, 'target' => '_blank', 'style' => 'color: #676767;font-size: 12px;')) : 'N/A';
                                    ?>
                                </td>
                                <td class="v-a-middle">
                                    <?php
                                    if ($advertisement['Banneradvertisement']['advertisement_place'] == 'Top') {
                                        $place = 'Top Area';
                                    }
                                    if ($advertisement['Banneradvertisement']['advertisement_place'] == 'Bottom') {
                                        $place = 'Bottom Area';
                                    }
                                    echo $place;
                                    ?>

                                </td>
                                <td class="v-a-middle">
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
                                <td class="v-a-middle"><?php echo date('d F Y', strtotime($advertisement['Banneradvertisement']['created'])); ?></td>
                                <td class="v-a-middle">
                                    <div id="loaderIDAct<?php echo $advertisement['Banneradvertisement']['id']; ?>" style="display:none;position:absolute;margin:-3px 0 0 -12px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                    <span id="status<?php echo $advertisement['Banneradvertisement']['id']; ?>">
                                        <?php
                                        if ($advertisement['Banneradvertisement']['status'] == '1') {
                                            echo $this->Ajax->link('<span class="icon-block col1"></span>', array('controller' => 'banneradvertisements', 'action' => 'deactivateBanneradvertisement/', $advertisement['Banneradvertisement']['id']), array('title' => 'Deactivate', 'update' => 'status' . $advertisement['Banneradvertisement']['id'], 'indicator' => 'loaderIDAct' . $advertisement['Banneradvertisement']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false));
                                        } else {
                                            echo $this->Ajax->link('<span class="icon-ok-1 col2"></span>', array('controller' => 'banneradvertisements', 'action' => 'activateBanneradvertisement/', $advertisement['Banneradvertisement']['id']), array('title' => 'Activate', 'update' => 'status' . $advertisement['Banneradvertisement']['id'], 'indicator' => 'loaderIDAct' . $advertisement['Banneradvertisement']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false));
                                        }
                                        ?>
                                    </span>
                                    <?php echo $this->Html->link('<span class="icon-pencil col3"></span>', array("controller" => "banneradvertisements", "action" => 'editBanneradvertisement/', $advertisement['Banneradvertisement']['id']), array('title' => 'Edit', 'escape' => false)); ?>
                                    <?php echo $this->Html->link('<span class="icon-trash col4"></span>', array('controller' => 'banneradvertisements', 'action' => 'deleteBanneradvertisement/', $advertisement['Banneradvertisement']['id']), array('title' => 'Delete', 'update' => 'deleted' . $advertisement['Banneradvertisement']['id'], 'indicator' => 'loaderID', 'class' => 'custom_link', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false)); ?>
                                </td>
                            </tr>
                            <?php
                            $i++;
                            $srNo++;
                        }
                        ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6 pt-10">
                        <div class="pull-left">
                            <?php __("Showing Page"); ?>
                            <?php
                            echo $this->Paginator->counter(
                                    'Banner Advertisements <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'
                            );
                            ?>
                            &nbsp;


                        </div>
                    </div>
                    <div class="col-md-6 pt-10">
                        <div class="s-pagination pull-right">
                            <?php echo $this->Paginator->first('<span class="icon-angle-double-left"></span>', array('escape' => false)); ?>&nbsp;
                            <?php if ($this->Paginator->hasPrev('Banneradvertisement')) echo $this->Paginator->prev('<span class="icon-left-open-1"></span>', array('class' => 'prev disabled', 'escape' => false)); ?>&nbsp;
                            <?php echo $this->Paginator->numbers(array('separator' => ' <i class="fa fa-chevron-right"></i> ', 'class' => 'badge-gray', 'escape' => false)); ?>&nbsp;
                            <?php if ($this->Paginator->hasNext('Banneradvertisement')) echo $this->Paginator->next('<span class="icon-right-open-1"></span>', array('class' => 'next', 'escape' => false)); ?>&nbsp;
                            <?php echo $this->Paginator->last('<span class="icon-angle-double-right"></span>', array('class' => 'fa fa-chevron-right', 'escape' => false)); ?>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="actdiv" class="outside">
            <div class="block-footer mogi">

                <?php echo $this->Form->text('Banneradvertisement.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                <?php echo $this->Form->text('Banneradvertisement.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                <?php
                if (isset($searchKey) && $searchKey != '') {
                    echo $this->Form->hidden('Banneradvertisement.bannerName', array('type' => 'hidden', 'value' => $searchKey));
                }
                ?>
                <?php echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?>
                <?php echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); ?>
                <?php echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'banneradvertisements', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?>

            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <div class="noUser">There are no banner advertisements added on site yet.</div>
    </div>
<?php } ?>




