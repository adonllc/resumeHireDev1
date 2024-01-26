
<?php if ($skills) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'skills', 'action' => 'index', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Skill", array("url" => "index", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Records <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Skill')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Skill')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <!--<th class="sorting_paging"><?php //echo $this->Paginator->sort('Skill.id', 'Skill Id'); ?></th>-->
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Skill.name', 'Skill Name'); ?></th>

                                    <th><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $adminLId = $this->Session->read('adminid');
                                $checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));
                                foreach ($skills as $skill) { ?>
                                    <tr>
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $skill['Skill']['id']; ?>" /></td>
                                        <!--<td data-title="Full Name"><?php //echo $skill['Skill']['id']; ?></td>-->
                                        <td data-title="Full Name"><?php echo $skill['Skill']['name']; ?></td>

                                        <td data-title="Action">
                                            <?php if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 6, 2)){ ?>
                                            <div id="loaderIDAct<?php echo $skill['Skill']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <!--Active deactive functionality-->   
                                            <span id="status<?php echo $skill['Skill']['id']; ?>">
                                                <?php
                                                if ($skill['Skill']['status'] == '1') {
                                                    echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'skills', 'action' => 'deactivateskill', $skill['Skill']['slug']), array('update' => 'status' . $skill['Skill']['id'], 'indicator' => 'loaderIDAct' . $skill['Skill']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                } else {
                                                    echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'skills', 'action' => 'activateskill', $skill['Skill']['slug']), array('update' => 'status' . $skill['Skill']['id'], 'indicator' => 'loaderIDAct' . $skill['Skill']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                }
                                                ?>
                                            </span>
                                            <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "skills", "action" => 'editskill', $skill['Skill']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit'));
                                             }
                                             if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 6, 3)){ 
                                                echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'skills', 'action' => 'deleteSkill', $skill['Skill']['slug']), array('update' => 'deleted' . $skill['Skill']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete'));
                                             } ?>

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
                    <?php echo $this->Form->text('Skill.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Skill.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>

                    <?php
                    if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 6, 2)){ 
                        echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'skills', 'action' => 'index', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); 
                        echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'skills', 'action' => 'index', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); 
                    }
                    if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 6, 3)){ 
                        echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'skills', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); 
                    } ?> 
                </div>
            </div>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Records <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('Skill')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Skill')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                </span>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Skill.name', array('type' => 'hidden', 'value' => $searchKey));
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">There are no skill to show for your search.</div></td>
            </tr>
        </table>
    </div>
    <?php }
?>
