<?php echo $this->Html->script('front/jquery.maskedinput.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#adminCandidate").validate();

        $(function(){
            $("#ExperienceFdate").mask("99/9999");
            $("#ExperienceTdate").mask("99/9999");
        });
    });

</script>


<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-candidates" ></i> Jobseeker » ', array('controller' => 'candidates', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> '.$candidateInfo['User']['first_name'].' '.$candidateInfo['User']['last_name'].'  »  Experience Details', 'javascript:void(0)', array('escape' => false));
?>



<!--main content start-->
<section id="main-content" class="site-min-height">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>

            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <span class="exlfink">Manage Jobseeker Experience Details </span>
                    </header>
                    <div class="row-fluid ">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                             <?php echo $this->Form->create(null, array("method" => "Post",'class' => 'form-inline', 'enctype' => 'multipart/form-data',  'id' => 'adminCandidate')); ?>
                                  <div class=" sadne_do">
                                        <div class="cc_name"><?php echo $this->Form->text('Experience.company_name', array('class' => "form-control required",'placeholder'=>'Company Name')) ?> </div>
                                        <div class="cc_fdate"><?php echo $this->Form->text('Experience.fdate', array('class' => "form-control required",'placeholder'=>'From(MM/YR)')) ?> </div>
                                        <div class="cc_fdate"><?php echo $this->Form->text('Experience.tdate', array('class' => "form-control required",'placeholder'=>'Until (MM/YR)')) ?> </div>
                                        <div class="cc_role"><?php echo $this->Form->text('Experience.job_role', array('class' => "form-control required",'placeholder'=>'Job Role')) ?> </div>
                                   </div>
                                <?php 
                                if(isset($this->data['Experience']['id']) && $this->data['Experience']['id'] !=''){
                                    echo $this->Form->hidden('Experience.id');
                                    echo $this->Form->submit('Update', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success'));
                                }else{
                                   echo $this->Form->submit('Add', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); 
                                }
                                 ?>
                                <?php echo $this->Html->link('Cancel', array('controller' => 'candidates', 'action' => 'index', $cslug), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <div id="listID">
                <?php echo $this->element("admin/candidates/experiences"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>



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
        
        var filename = document.getElementById("CertificateDocument").value;
        var filetype = ['jpg', 'jpeg', 'png', 'gif'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for Certificate.");
                document.getElementById("CertificateDocument").value = '';
                return false;
            } else {
                var fi = document.getElementById('CertificateDocument');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 2097152) {
                    alert('Maximum 2MB file size allowed for Certificate.');
                    document.getElementById("CertificateDocument").value = '';
                    return false;
                }
            }
        }        
    }
  
</script>
