


  
           
            
                <?php //echo $this->Form->create("Job", array('action' => 'listing', 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob2', 'name' => 'searchJob', 'autocomplete' => 'off')); ?>                        

                <?php
                // pr($jobs);
                if (isset($searchkey) && !empty($searchkey)) {
                    echo $this->Form->input('searchkey', array('type' => 'hidden', 'value' => $searchkey));
                }
                ?>
              <div class="container">
                    <div id="listID">
                        <?php echo $this->element('jobs/filter_section'); ?>
                    </div>
                </div>
    

<script>
function addalertdelegate(){
    if($('#talertemail').val() == ""){
        alert("Please enter email address");
    }else{
        $('#tjobalertsending').html('');
        if (/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)(\s+)?$/.test($('#talertemail').val()))  
        {  
             $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/users/getalert/"+$('#talertemail').val(),
                cache: false,
                data:$('#searchJob1').serialize(),
                beforeSend: function () {
                    $("#loaderID").show();
                },
                complete: function () {
                    $("#loaderID").hide();
                },
                success: function (result) {
                    $("#loaderID").hide();
                    if (result) {
                        var obj = JSON.parse(result);
                        if(obj.success == 0){
                            $('#tjobalertsending').html(obj.message);
                        }else{
                            $('#tjobalertmessage').html(obj.message);
                        }
                        

                    }

                }
            });
        }else{
            alert("<?php echo __d('user', 'Please put valid email address', true);?>");
        }
       
    }
    return false;
}
function getresumeForm(){
     $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/jobs/getresumeForm",
                cache: false,
                beforeSend: function () {
                    $("#loaderID").show();
                },
                complete: function () {
                    $("#loaderID").hide();
                },
                success: function (result) {
                    $("#loaderID").hide();
                    if (result) {
                         $('#applypopjob').html(result); 
                        $('#applypopjob').addClass("popupc"); 

                    }

                }
            });
}
function closepop(){
$('#applypopjob').removeClass("popupc"); 
    $('#applypopjob').html("");
}
</script>
<div  id="applypopjob"></div>