<script type="text/javascript">
    $(document).ready(function() {
        $("#canExp").validate();
    });
</script>
<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
                    <div class="info_dv">
                        <div class="heads">Manage Certificates</div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    
                            <div class="manage_cet">
                                <?php echo $this->Form->create(null, array("method" => "Post",'class' => 'form-inline', 'enctype' => 'multipart/form-data',  'id' => 'canExp')); ?>
                                <p>Upload scanned copy of certificate </p>
                                <div class="form-group">
                                    <?php echo $this->Form->input('Certificate.document', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default required', 'onchange' => 'imageValidation()')) ?>
                                    <span >Supported File Types: gif, jpg, jpeg, png (Max. 2MB). </span>
                                </div>
                                <?php echo $this->Form->submit('Upload', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                                <?php echo $this->Html->link('Cancel', array('controller' => 'candidates', 'action' => 'myaccount', ''), array('escape' => false, 'class' => 'btn btn-danger','rel'=>'nofollow')); ?>
                            <?php echo $this->Form->end(); ?>
                            </div>
                            <div class="manage_cet">
                             <div class="job_scroll">
                                <div class="job_content" id='listID'>
                                    <?php echo $this->element("candidates/certificates"); ?>
                                </div>
                             </div>
                                
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
                alert(ext + " file not allowed for scanned copy.");
                document.getElementById("CertificateDocument").value = '';
                return false;
            } else {
                var fi = document.getElementById('CertificateDocument');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 2097152) {
                    alert('Maximum 2MB file size allowed for scanned copy.');
                    document.getElementById("CertificateDocument").value = '';
                    return false;
                }
            }
        }        
    }
    
   
</script>


