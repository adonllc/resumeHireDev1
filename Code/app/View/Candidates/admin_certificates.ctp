
<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#adminCandidate").validate();
    });
    
</script>


<link href="<?php echo HTTP_PATH; ?>/css/front/uploadfilemulti.css" rel="stylesheet">

<script src="<?php echo HTTP_PATH; ?>/js/front/jquery.fileuploadmulti.min.js" charset="utf-8"></script>
<script>

    $(document).ready(function() {

        var settings = {
            url: "<?php echo HTTP_PATH . "/candidates/uploadmultipleimages" ?>",
            method: "POST",
            dragDropStr:"<span><b></b></span>",
            allowedTypes: "jpg,png,gif,jpeg,doc,docx,pdf",
            fileName: "data[Certificate][document]",
            multiple: true,
            maxFileSize:1049*1000*4,
            // maxFileCount:<?php //echo UPLOAD_MAX_IMAGE;              ?>,
            onSelect: function(response, data_re)
            {
                var input = $("#images");
                if (input.val())
                    var array = input.val().split(",");
                else
                    var array = [];
            },
            onSuccess: function(response, data_re, xhr)
            {
                var status = $("#status");
                status.html('');
                var data = $.parseJSON(data_re);
                if (data.status == 'success') {

                    var counter = 0;

                    var html = '';
                    var image_arr = [];

                    var input = $("#images");
                    if (input.val())
                        var array = input.val().split(",");
                    else
                        var array = [];
                    
                    var image = '<?php echo DISPLAY_TMP_CERTIFICATE_PATH; ?>' + data.image;
                    var imagename = data.image;
                    var id1 = imagename.replace('.', '-');
                    if(data.type=='image'){
                        html += "<div class='image_thumb' alt='" + imagename + "' id='delete_" + id1 + "'> <span class='temp-image-section'><img  src='<?php echo DISPLAY_TMP_CERTIFICATE_PATH ?>" + data.image + "'/></span><span class='delete_image' alt='" + data.image + "'>X </span></div>";
                    }else if(data.type=='doc'){
                        //html += "<div class='doc_fukll_name'  alt='" + imagename + "' id='delete_" + id1 + "'><div class='doc_files_border'><span class='temp-image-section'><a href='<?php echo HTTP_PATH; ?>/candidates/downloadDocCertificateTemp/"+ data.image +"' class='dfasggs' >"+imagename.substring(6)+"</a><span class='close_icon_for' alt='" + data.image + "'>X </span></span></div></div>";  
                        html += "<div class='doc_fukll_name'  alt='" + imagename + "' id='delete_" + id1 + "'><div class='doc_files_border'><span class='temp-image-section'><a href='<?php echo HTTP_PATH; ?>/candidates/downloadDocCertificateTemp/"+ data.image +"' class='dfasggs' >"+imagename.substring(6)+"</a><span class='close_icon_for' alt='" + data.image + "'>X </span></span></div></div>";  
                    }
                   
                    array.push(data.image);

                    $("#images").val();

                    input.val(array);
                    if(data.type=='image'){
                        $(".check").after(html);
                    }else if(data.type=='doc'){
                        $(".check_doc").after(html);
                    }
                    //$(".loading-image").hide();

                } else {
                    alert(data.message);
                    //$(".loading-image").hide();
                }
            }
            ,
            afterUploadAll: function()
            { 
                $(".upload-statusbar").remove();
            },
            onError: function(files, status, errMsg)
            {
                $("#status").html("<font color='red'>Upload is Failed</font>");
            }
        }
        $("#mulitplefileuploader").uploadFile(settings);
                
        $(document).on("click", ".delete_image , .close_icon_for", function() {
            
            var id = $(this).attr('alt');
            var id1 = id.replace('.', '-');
            $("#delete_" + id1).hide();
            var $input = $("#images");
            
            var arrayOld = $input.val().split(",");
            arrayNew = $.grep(arrayOld, function(image_names, i) {
                //var substr = image_names.split('_');
                //return substr[0] !== id;
                return image_names !== id;
            });
            $input.val(arrayNew);
        })

       
        
        var counter = $('#UserCoverLetterCount').val();
        $("#addButton").click(function () {
            
            if(counter>15){
                alert('You can not add cover latter more than 15.');
                return false;
            }
            $('#MediaGroup').append('<div class="media_box" id="media_box'+counter+'"><span class="cover_ttt"><input type="text"  value="" placeholder="Cover Letter Title" class="required" maxlength="30" name="data[CoverLetter]['+counter+'][title]"></span><span class="cover_desss"><textarea  placeholder="Cover Letter" class="required" name="data[CoverLetter]['+counter+'][description]"></textarea></span><span class="close_icon3_1"><img src="<?php echo HTTP_IMAGE ?>/close.png" id="'+counter+'" class="close" /></span></div>');
            counter++; 
            
            $('img.close').last().click (function () { 
                $("#media_box" +(this.id)).remove();
                counter--;
            });
        });
       
    });
    
</script>

<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-users" ></i> Jobseekers » ', array('controller' => 'candidates', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> ' . $candidateInfo['User']['first_name'] . ' ' . $candidateInfo['User']['last_name'] . '  » Certificates', 'javascript:void(0)', array('escape' => false));
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
                        <span class="exlfink">Manage Jobseeker Document/Certificates </span>
                    </header>
                    <div class="row-fluid ">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                            <?php echo $this->Form->create(null, array("method" => "Post", 'class' => 'form-inline', 'enctype' => 'multipart/form-data', 'id' => 'adminCandidate')); ?>
                            <div class="form_lst hehehms">
                                <label class="hahaclass">CV Document/Certificates <span class="star_red"></span><span class="subcat_help_text">Supported File Types: pdf, doc and docx, gif, jpg, jpeg, png (Max. 4 MB).</span></label>
                                <div class="rltv">
                                    <div id="mulitplefileuploader">CHOOSE FILE </div>
                                    <!--                                    <div class="supported-types">Supported File Types: gif, jpg, jpeg, png (Max. 4 MB)</div>-->
                                    <div class="supported-types">Min file size 150 X 150 pixels for image</div>

                                    <input type="hidden" id="images" name="data[Certificate][document]" value="" >

                                    <div class="hmdnd no-margin-row">

                                        <label>&nbsp;</label>
                                        <?php if ($showOldImages || $showOldDocs) { ?>
                                            <div class="all-uploaded-images">
                                                <div class="check_doc"> </div>
                                                <?php
                                                foreach ($showOldDocs as $showOldDoc) {
                                                    $doc = $showOldDoc['Certificate']['document'];
                                                    if (!empty($doc) && file_exists(UPLOAD_CERTIFICATE_PATH . $doc)) {
                                                        ?>
                                                        <div id="<?php echo $showOldDoc['Certificate']['slug']; ?>" alt="" class="doc_fukll_name">
                                                            <div class="doc_files_border">
                                                                <span class="temp-image-section">
                                                                    <?php echo $this->Html->link(substr($doc, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $doc), array('class' => 'dfasggs required')); ?>    
                                                                    <span class="close_icon_for" onclick="deleteOldImage('<?php echo $showOldDoc['Certificate']['slug']; ?>')">
                                                                        X
                                                                    </span>
                                                                </span>

                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                <div class="check"> </div>

                                                <?php
                                                foreach ($showOldImages as $showOldImage) {
                                                    $image = $showOldImage['Certificate']['document'];
                                                    if (!empty($image) && file_exists(UPLOAD_CERTIFICATE_PATH . $image)) {
                                                        ?>
                                                        <div id="<?php echo $showOldImage['Certificate']['slug']; ?>" alt="" class="image_thumb">
                                                            <span class="temp-image-section">
                                                                <img src="<?php echo DISPLAY_CERTIFICATE_PATH . $image; ?>">
                                                            </span>
                                                            <span class="delete_image" onclick="deleteOldImage('<?php echo $showOldImage['Certificate']['slug']; ?>')">
                                                                X
                                                            </span>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        <?php } else {
                                            ?>
                                            <div class="all-uploaded-images">
                                                <div class="check_doc"> </div>
                                                <div class="check"> </div>
                                            </div>
                                        <?php } ?>


                                    </div>
                                </div>
                            </div>

                            <div style=" margin-top:10px;"></div>
                            <div class="clr"></div>
                            <?php echo $this->Form->submit('Upload', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Cancel', array('controller' => 'candidates', 'action' => 'index', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <div id="listID">
                <?php //echo $this->element("admin/candidates/certificates"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>



<script>
    function deleteOldImage(slug){
        $('#'+slug).hide();
        $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/candidates/deleteCertificacte/"+slug,
            cache: false,
            success: function(result) {
               
            }
        });
    }
    //    function updateCity(stateId){
    //        $.ajax({
    //            type : 'POST',
    //            url: "<?php echo HTTP_PATH; ?>/cities/getStateCity/User/"+stateId,
    //            cache: false,
    //            success: function(result) {
    //                $("#updateCity").html(result);
    //            }
    //        });
    //    }
    function updateStateCity(postCode){
        
        $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/cities/getStateCityByPostCode/User/"+postCode,
            cache: false,
            success: function(result) {
                $("#updateCityState").html(result);
            }
        });
        
    }
</script>

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

    
    function cvValidation() {
        
        var filename = document.getElementById("input_choose_cv").value;
        var filetype = ['pdf', 'doc', 'docx'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for CV Document.");
                document.getElementById("input_choose_cv").value = '';
                return false;
            } else {
                var fi = document.getElementById('input_choose_cv');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 4194304) {
                    alert('Maximum 4MB file size allowed for CV Document.');
                    document.getElementById("input_choose_cv").value = '';
                    return false;
                }
            }
        }        
    }
    
</script>



<script>
    //check if browser supports file api and filereader features
    if (window.File && window.FileReader && window.FileList && window.Blob) {
    
        //this is not completely neccesary, just a nice function I found to make the file size format friendlier
        //http://stackoverflow.com/questions/10420352/converting-file-size-in-bytes-to-human-readable
        function humanFileSize(bytes, si) {
            var thresh = si ? 1000 : 1024;
            if(bytes < thresh) return bytes + ' B';
            var units = si ? ['kB','MB','GB','TB','PB','EB','ZB','YB'] : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
            var u = -1;
            do {
                bytes /= thresh;
                ++u;
            } while(bytes >= thresh);
            return bytes.toFixed(1)+' '+units[u];
        }


        //this function is called when the input loads an image
        function renderImage(file){ 
            var reader = new FileReader();
            reader.onload = function(event){
                the_url = event.target.result
                //of course using a template library like handlebars.js is a better solution than just inserting a string
                $('#preview').html("<img src='"+the_url+"' />")
                $('#name').html(file.name)
                $('#size').html(humanFileSize(file.size, "MB"))
                $('#type').html(file.type)
            }
    
            //when the file is read it triggers the onload event above.
            reader.readAsDataURL(file);
        }

  
        //this function is called when the input loads a video
        function renderVideo(file){
            var reader = new FileReader();
            reader.onload = function(event){
                the_url = event.target.result
                //of course using a template library like handlebars.js is a better solution than just inserting a string
                $('#data-vid').html("<video width='400' controls><source id='vid-source' src='"+the_url+"' type='video/mp4'></video>")
                $('#name-vid').html(file.name)
                $('#size-vid').html(humanFileSize(file.size, "MB"))
                $('#type-vid').html(file.type)
                //alert(humanFileSize(file.size, "MB"));

            }
    
            //when the file is read it triggers the onload event above.
            reader.readAsDataURL(file);
        }

  

        //watch for change on the 
        $("#the-photo-file-field" ).change(function() {
            console.log("photo file has been chosen")
            //grab the first image in the fileList
            //in this example we are only loading one file.
            console.log(this.files[0].size)
            renderImage(this.files[0])

        });
  
        $( "#input_choose_cv" ).change(function() {
            console.log("video file has been chosen")
            //grab the first image in the fileList
            //in this example we are only loading one file.
            //console.log(this.files[0].size)
            renderVideo(this.files[0])

        });

    } else {

        //alert('The File APIs are not fully supported in this browser.');

    }
</script>
