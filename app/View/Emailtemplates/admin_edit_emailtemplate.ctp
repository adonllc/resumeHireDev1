<script type="text/javascript">
    $(document).ready(function() {
        
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s`~!@#$%^&*()+={}|;:'",.\/?\\-]+$/.test(value);
            }, "Please do not enter  special character like < or >");
            $("#addPage").validate();
        });

        function InsertHTML(val) {
            if(val != ''){
                var newStr = '{'+val+'}';
                var oEditor = FCKeditorAPI.GetInstance('data[Emailtemplate][template]') ;
                oEditor.InsertHtml(val);
            }
        }
        function InsertHTMLDE(val) {
            if(val != ''){
                var newStr = '{'+val+'}';
                var oEditor = FCKeditorAPI.GetInstance('data[Emailtemplate][template_de]') ;
                oEditor.InsertHtml(val);
            }
        }

        function Inserttag(val) {
            var myField='sub';
            if(val != ''){			
                var text = document.getElementById('sub').value;
                document.getElementById('sub').value+=" "+val;
            }
        }
        function InserttagDE(val) {
            var myField='sub_de';
            if(val != ''){			
                var text = document.getElementById('sub_de').value;
                document.getElementById('sub_de').value+=" "+val;
            }
        }
        
           function InsertHTMLFRA(val) {
            if(val != ''){
                var newStr = '{'+val+'}';
                var oEditor = FCKeditorAPI.GetInstance('data[Emailtemplate][template_fra]') ;
                oEditor.InsertHtml(val);
            }
        }
        
         function InserttagFRA(val) {
            var myField='sub_de';
            if(val != ''){			
                var text = document.getElementById('sub_fra').value;
                document.getElementById('sub_fra').value+=" "+val;
            }
        }
</script>               

<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-envelope-o" ></i> Email Template Management » ', array('controller' => 'emailtemplates', 'action' => 'index', ''), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-pencil-square-o"></i> Edit Email Template Details', 'javascript:void(0)', array('escape' => false));
?>

<?php
echo $this->Form->create('Emailtemplate', array('enctype' => 'multipart/form-data', 'id' => 'addPage'));
?>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Email Template Detail</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Template Details:
                        <span class="exportlink btn btn-success btn-xs pull-right">
                            <?php echo $this->Html->link('Test Mail', array('controller' => 'emailtemplates', 'action' => 'testmail', $this->request->data['Emailtemplate']['static_email_heading']), array('escape' => false, 'title' => 'Test Mail')); ?>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Emailtemplate.title', array('size' => '20', 'label' => '', 'div' => false, 'class' => "form-control alphanumeric required")) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Variables to use <div class="required_field"></div></label>
                            <div class="controls">
                                <?php $options = explode(',', $Action_options);
                                    foreach ($options as $v => $k) { ?>
                                        <button onclick="Inserttag(this.value)" value="<?php echo $k; ?>" type="button" class="btn"><?php echo $k; ?></button>
                                <?php } ?>
                                <br><em style="padding:5px 0; display:inline-block;">Note* : click on above variable buttons to use these in below subject on behalf of dynamic values (like : username: [!username!])</em><br />

                                <label class="col-sm-2 col-sm-2 control-label">Subject <div class="required_field">*</div></label>
                                <div class="col-sm-10" >
                                    <?php echo $this->Form->text('Emailtemplate.subject', array('size' => '20', 'label' => '', 'div' => false, 'class' => "form-control required", 'id' => 'sub')) ?>
                                </div>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Variables to use <div class="required_field"></div></label>
                            <?php
                                //echo $this->Form->label("Emailtemplate.variables', 'Action :*',array("class"=>"control-label") ); 
                                //$Action_options	=	Configure::read('Action_options'); 
                            ?>
                            <div class="controls">
                                <?php $action = array();
                                foreach ($options as $v => $k) { ?>
                                    <button onclick="InsertHTML(this.value)" value="<?php echo $k; ?>" type="button" class="btn"><?php echo $k; ?></button>
                                <?php } //echo $this->Form->hidden(Emailtemplate.variables"); ?>
                                <br><em>Note* : click on above variable buttons to use these in below template on behalf of dynamic values (like : username: [!username!])</em>
                                <span class="help-inline" style="color: #B94A48;">
                                    <?php //echo $this->Form->error(Emailtemplate.variables', array('wrap' => false) ); ?>
                                </span>
                                <span style = "padding-left:20px;padding-top:0px; valign:top">
                                    <?php
                                    //	echo $this->Html->link('Insert Variable', 'javascript:void(0)',array('class'=>'btn  btn-success','escape' => false,'onclick' => 'return InsertHTML()',"escape" => false));
                                    ?>
                                </span>
                            </div>
                        </div>                    
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Body <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Emailtemplate', 'template'), $this->Html->base, $this->data['Emailtemplate']['template']); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Variables to use <div class="required_field"></div></label>
                            <div class="controls">
                                <?php $options = explode(',', $Action_options);
                                    foreach ($options as $v => $k) { ?>
                                        <button onclick="InserttagDE(this.value)" value="<?php echo $k; ?>" type="button" class="btn"><?php echo $k; ?></button>
                                <?php } ?>
                                <br><em style="padding:5px 0; display:inline-block;">Note* : click on above variable buttons to use these in below subject on behalf of dynamic values (like : username: [!username!])</em><br />

                                <label class="col-sm-2 col-sm-2 control-label">Subject(German) <div class="required_field">*</div></label>
                                <div class="col-sm-10" >
                                    <?php echo $this->Form->text('Emailtemplate.subject_de', array('size' => '20', 'label' => '', 'div' => false, 'class' => "form-control required", 'id' => 'sub_de')) ?>
                                </div>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Variables to use <div class="required_field"></div></label>
                            <?php
                                //echo $this->Form->label("Emailtemplate.variables', 'Action :*',array("class"=>"control-label") ); 
                                //$Action_options	=	Configure::read('Action_options'); 
                            ?>
                            <div class="controls">
                                <?php $action = array();
                                foreach ($options as $v => $k) { ?>
                                    <button onclick="InsertHTMLDE(this.value)" value="<?php echo $k; ?>" type="button" class="btn"><?php echo $k; ?></button>
                                <?php } //echo $this->Form->hidden(Emailtemplate.variables"); ?>
                                <br><em>Note* : click on above variable buttons to use these in below template on behalf of dynamic values (like : username: [!username!])</em>
                                <span class="help-inline" style="color: #B94A48;">
                                    <?php //echo $this->Form->error(Emailtemplate.variables', array('wrap' => false) ); ?>
                                </span>
                                <span style = "padding-left:20px;padding-top:0px; valign:top">
                                    <?php
                                    //	echo $this->Html->link('Insert Variable', 'javascript:void(0)',array('class'=>'btn  btn-success','escape' => false,'onclick' => 'return InsertHTML()',"escape" => false));
                                    ?>
                                </span>
                            </div>
                        </div>                    
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Body (German) <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Emailtemplate', 'template_de'), $this->Html->base, $this->data['Emailtemplate']['template_de']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Variables to use <div class="required_field"></div></label>
                            <div class="controls">
                                <?php $options = explode(',', $Action_options);
                                    foreach ($options as $v => $k) { ?>
                                        <button onclick="InserttagFRA(this.value)" value="<?php echo $k; ?>" type="button" class="btn"><?php echo $k; ?></button>
                                <?php } ?>
                                <br><em style="padding:5px 0; display:inline-block;">Note* : click on above variable buttons to use these in below subject on behalf of dynamic values (like : username: [!username!])</em><br />

                                <label class="col-sm-2 col-sm-2 control-label">Subject(French) <div class="required_field">*</div></label>
                                <div class="col-sm-10" >
                                    <?php echo $this->Form->text('Emailtemplate.subject_fra', array('size' => '20', 'label' => '', 'div' => false, 'class' => "form-control required", 'id' => 'sub_fra')) ?>
                                </div>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Variables to use <div class="required_field"></div></label>
                            <?php
                                //echo $this->Form->label("Emailtemplate.variables', 'Action :*',array("class"=>"control-label") ); 
                                //$Action_options	=	Configure::read('Action_options'); 
                            ?>
                            <div class="controls">
                                <?php $action = array();
                                foreach ($options as $v => $k) { ?>
                                    <button onclick="InsertHTMFRA(this.value)" value="<?php echo $k; ?>" type="button" class="btn"><?php echo $k; ?></button>
                                <?php } //echo $this->Form->hidden(Emailtemplate.variables"); ?>
                                <br><em>Note* : click on above variable buttons to use these in below template on behalf of dynamic values (like : username: [!username!])</em>
                                <span class="help-inline" style="color: #B94A48;">
                                    <?php //echo $this->Form->error(Emailtemplate.variables', array('wrap' => false) ); ?>
                                </span>
                                <span style = "padding-left:20px;padding-top:0px; valign:top">
                                    <?php
                                    //	echo $this->Html->link('Insert Variable', 'javascript:void(0)',array('class'=>'btn  btn-success','escape' => false,'onclick' => 'return InsertHTML()',"escape" => false));
                                    ?>
                                </span>
                            </div>
                        </div>                    
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Body (French) <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Emailtemplate', 'template_fra'), $this->Html->base, $this->data['Emailtemplate']['template_fra']); ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-lg-10">
            <?php echo $this->Form->input('Emailtemplate.id', array('type' => 'hidden')); ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php //echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger'));  ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'emailtemplates', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
        </div>
        <!-- page end -->
    </section>
</section>
<?php echo $this->Form->end(); ?>