<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addCat").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-child" ></i> List Jobs » ', '/admin/candidates', array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus" ></i> Import Jobs');
?>

<?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'id' => 'addCat', 'name' => 'addCat'));?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Import Jobs</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                         <span class="exlfink">Import Jobs </span>
<!--                        <span class="exportlink btn btn-success btn-xs pull-right">
                            <i class="fa fa-cloud-download" aria-hidden="true"></i> 
                            <?php echo $this->Html->link('<span class="icon-download col4">Sample File download</span>', array('controller' => 'jobs', 'action' => 'downloadfile'), array('title'=>'Sample File download', 'indicator' => 'loaderID', 'class' => 'custom_link', 'escape' => false));?>
                        </span>-->
                       
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">XML Feed URL <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php // echo $this->Form->file('User.filedata', array('size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>
                                <?php echo $this->Form->text('User.url', array('class' => "required form-control keyword-box", 'autocomplete' => 'off', 'data-suggesstion' => 'jobkeyword-box', 'data-search' => 'Job')) ?>  
                                <!--<span>Supported File Types: xml. Download sample xml file, put value under each column and do not add/remove or change header in xml.</span>-->
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <div class="col-lg-10">
            <?php echo $this->Form->submit('Next', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php // echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger', 'id' => 'resetForm')); ?>
        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>

<script>
    function in_array(needle, haystack) {
        for (var i = 0, j = haystack.length; i < j; i++) {
            if (needle == haystack[i])
                return true;
        }
        return false;
    }

    function getExt(filename) {
        var dot_pos = filename.lastIndexOf(".");
        if (dot_pos == -1)
            return "";
        return filename.substr(dot_pos + 1).toLowerCase();
    }



    function imageValidation() {

        var filename = document.getElementById("ResourceFiledata").value;

        var filetype = ['xls','xlsx'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed.");
                return false;
            } else {/*
                var fi = document.getElementById('DepartmentFiledata');
                var filesize = fi.files[0].size;//check uploaded file size
                //                    if(filesize > 2097152){
                //                        alert('Maximum 2MB file size allowed.');
                //                        return false;
                //                    }
                if (filesize > 2097152) {
                    alert('Maximum 2MB file size allowed.');
                    return false;
                }*/
            }
        }
        return true;
    }

</script>