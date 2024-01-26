<?php echo $this->Html->script('jquery/jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function(){
            jQuery.validator.addMethod("url1", function(value, element) { 
                return this.optional(element) ||  /^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/.test(value);
            }, "Please enter a valid URL.");
            $("input").focus(function(){
                    $("#"+this.id).removeClass( "form-error" );
            });        
        $("#addBanner").validate();
    });
</script>
<script type="text/javascript">

function showDiv(){
	if(document.getElementById('Type1').checked){
		document.getElementById("advertisementimage").style.display = "table-row";
		document.getElementById("advertisementimage2").style.display = "table-row";
		document.getElementById("advertisementimage3").style.display = "table-row";
                                    document.getElementById("advertisementimage4").style.display = "table-row";
                                    document.getElementById("advertisementcode").style.display = "none";
		document.getElementById("advertisementText").style.display = "none";
                                    $('#BanneradvertisementUrl').addClass('required');
                                    $('#BanneradvertisementUrl').addClass('url1');
                                    <?php if(empty($this->data['Banneradvertisement']['old_image'])){?>
                                        $('#BanneradvertisementImage').addClass('required');
                                        $('#requiredid').html('*');
                                    <?php }?>
                                    $('#BanneradvertisementCode').removeClass('required');
                                    $('#BanneradvertisementText').removeClass('required');
	}else if(document.getElementById('Type2').checked){
		document.getElementById("advertisementimage").style.display = "none";
		document.getElementById("advertisementimage2").style.display = "none";
		document.getElementById("advertisementimage3").style.display = "none";
                                    document.getElementById("advertisementimage4").style.display = "none";
		document.getElementById("advertisementText").style.display = "none";
		document.getElementById("advertisementcode").style.display = "table-row";
                                    $('#BanneradvertisementUrl').removeClass('required');
                                    $('#BanneradvertisementUrl').removeClass('url1');
                                    //$('#BanneradvertisementImage').removeClass('required');
                                    $('#BanneradvertisementCode').addClass('required');
                                    $('#BanneradvertisementText').removeClass('required');
	}else{
		document.getElementById("advertisementimage").style.display = "table-row";
		document.getElementById("advertisementimage2").style.display = "none";
		document.getElementById("advertisementimage3").style.display = "none";
                                    document.getElementById("advertisementimage4").style.display = "none";
		document.getElementById("advertisementcode").style.display = "none";
		document.getElementById("advertisementText").style.display = "table-row";
                                    $('#BanneradvertisementUrl').addClass('required');
                                    $('#BanneradvertisementUrl').addClass('url1');
                                    //$('#BanneradvertisementImage').removeClass('required');
                                    $('#BanneradvertisementCode').removeClass('required');
                                    $('#BanneradvertisementText').addClass('required');
	}

  }
</script>
<?php
$this->Html->addCrumb('Dashboard » ', '/admin/admins');
$this->Html->addCrumb('Banner Advertisement Management » ', '/admin/banneradvertisements');
$this->Html->addCrumb('Edit Banner Advertisement');
?>
<div class="ful_wdith right_con">
    <div class="wht_bg">
        <div class="columns mrgih_tp">
            <fieldset>
                <legend>Edit Banner Advertisement Details</legend>

                <?php echo $this->Form->create('Banneradvertisement',array('method'=>'POST','name'=>'frmCreateMember','id'=>'addBanner','enctype'=>'multipart/form-data')); ?>
                <span class="require" style="float: left;width: 100%;">* Please note that all fields that have an asterisk (*) are required. </span>
                <?php
                if ($this->Session->check('error_msg')) {
                    echo "<div class='ActionMsgBox error' id='msgID'><ul><li>" . $this->Session->read('error_msg') . "</li></ul></div>";
                    $this->Session->delete("error_msg");
                } elseif ($this->Session->check('success_msg')) {
                    echo "<div class='SuccessMsgBox success' id='msgID'><ul><li>" . $this->Session->read('success_msg') . "</li></ul></div>";
                    $this->Session->delete("success_msg");
                }
                ?>
                 <div class="columns">
                    <p class="colx2-left">
                        <label for="old-password">Place of Advertisement <span class="require">*</span></label>
                        <span class="relative">
                            <?php 				    
                                if($this->data['Banneradvertisement']['advertisement_place'] == 'Top'){
                                    $place = 'Top Area';
                                }						    
                                if($this->data['Banneradvertisement']['advertisement_place'] == 'Bottom'){
                                    $place = 'Bottom Area';
                                }						    
                            echo $place; ?>
                        </span>
                    </p>
                </div>
                <div class="columns">
                    <p class="colx2-left">
                        <label for="old-password">Title <span class="require">*</span></label>
                        <span class="relative">
                            <?php echo $this->Form->text('Banneradvertisement.title', array('maxlength' => '255', 'size'=>'50','label'=>'','div'=>false,'class'=>"full-width required alphanumeric"));?>
                        </span>
                    </p>
                </div>
                <div class="columns">
                    <p class="colx2-left">
                        <label for="old-password">Advertisement Type <span class="require">*</span></label>
                        <span class="relative">
                            <?php
                                //'2'=>'Html Code'
                                //$options = array('1' => 'Picture Adverts', '2' => 'Google Adverts','3'=>'Text Adverts');
                                $options = array('1' => 'Picture Adverts', '2' => 'Google Adverts');
                                $attributes = array('id' => 'type', 'legend' => false, 'default' => '1', 'onclick' => 'showDiv();');
                                echo $this->Form->radio('Banneradvertisement.type', $options, $attributes);
                                ?>
                        </span>
                    </p>
                </div>
                <div class="columns" id = "advertisementimage" <?php if($type == 2) { ?>style="display:none;" <?php } ?> >
                    <p class="colx2-left">
                        <label for="old-password">URL <span class="require">*</span></label>
                        <span class="relative">
                            <?php if($type == 1 || $type == 3){
                                            $urlclass = 'required url1';
                                        }else{
                                            $urlclass = '';
                                        }
                                    echo $this->Form->text('Banneradvertisement.url', array('size'=>'50','label'=>'','div'=>false,'class'=>$urlclass));?>
                                    <div class="clr"></div>
                            <span class="help_text">(Enter URL Like http://www.google.com)</span>
                        </span>
                    </p>
                </div>
                <div class="columns" id = "advertisementText" <?php if($type == 2 || $type == 1) { ?>style="display:none;" <?php } ?>>
                    <p class="colx2-left">
                       <label for="old-password">Advertisement Text : <span class="require">*</span></label>
                        <span class="relative">
                            <?php if($type == 3){
                                    $textclass = 'required';
                                }else{
                                    $textclass = '';
                                }
                            echo $this->Form->textarea('Banneradvertisement.text', array('class' =>$textclass, 'cols' => '42', 'rows' => '5')) ?>		
                        </span>
                    </p>
                </div>
                        			
                <div class="columns" id = "advertisementimage2" <?php if($type == 2 || $type == 3) { ?>style="display:none;" <?php } ?>>
                    <p class="colx2-left">
                        <label for="old-password">Advertisement Image : <span class="require" id="requiredid"></span></label>
                        <span class="relative">
                            <?php if($type == 1){
                                    //$imageclass = 'required';
                                    $imageclass = '';
                                }else{
                                    $imageclass = '';
                                }
                            echo $this->Form->file('Banneradvertisement.image',array('class'=>$imageclass ,'size'=>'38',))?>
                            <div class="clr"></div>
                            <span class="help_text"> Please Upload File Type: jpg,jpeg,gif,png. Max Size 2Mb. </span>
                        </span>
                    </p>
                </div>
                <div class="columns" id = "advertisementimage3" <?php if($type == 2 || $type == 3) { ?>style="display:none;" <?php } ?>>
                    <p class="colx2-left">
                        <span class="relative">Standard size of Advertisement images :-<br/><br/>
                                 1) Top Area (Width:1147px, Height:143px)<br/>
                            2) Bottom Area (Width:1149px, Height:94px)<br/>
                        </span>
                    </p>
                </div>
                <div class="columns" id = "advertisementimage4" <?php if($type == 2 || $type == 3) { ?>style="display:none;" <?php } ?>>
                    <p class="colx2-left">
                        <label for="old-password">&nbsp;</label>
                        <span class="relative">
                            <?php if(!empty ($this->data['Banneradvertisement']['image'])){
                                            if($this->data['Banneradvertisement']['advertisement_place'] == 'Bottom'){
                                                    echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_BANNER_AD_IMAGE_PATH.$this->data['Banneradvertisement']['image'] . "&h=90&zc=3&q=100",array());
                                            }else{
                                                echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_BANNER_AD_IMAGE_PATH.$this->data['Banneradvertisement']['image']. "&w=200&h=150&zc=3&q=100",array());
                                            }
                                        }
                            ?>
                </span>
                    </p>
            </div>
			
                <div class="columns" id = "advertisementcode" <?php if($type == 1 || $type == 3) { ?>style="display:none;" <?php } ?>>
                    <p class="colx2-left">
                        <label for="old-password">Advertisement HTML Code: <span class="require">*</span></label>
                        <span class="relative">
                            <?php if($type == 2){
                                $codeclass = 'required';
                            }else{
                                $codeclass = '';
                            }
                            echo $this->Form->textarea('Banneradvertisement.code', array('class' => $codeclass, 'cols' => '42', 'rows' => '5')) ?>
                        </span>
                    </p>
                </div>
                
            </fieldset>
            <fieldset class="grey-bg no-margin">

                <p class="input-with-button margin-top">
                    <?php 
                        echo $this->Form->input('Banneradvertisement.advertisement_place', array('type'=>'hidden','value'=>$this->data['Banneradvertisement']['advertisement_place']));
                        echo $this->Form->input('Banneradvertisement.old_image', array('type'=>'hidden','value'=>$this->data['Banneradvertisement']['image']));
                        echo $this->Form->input('Banneradvertisement.id', array('type'=>'hidden'));
                    ?>
                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'type' => 'submit', 'onclick' => 'return imageValidation();','class' => 'btn btn-success btn-cons')); ?>
                    <?php echo $this->Html->link('Cancel',array('controller'=>'banneradvertisements','action'=>'index/'), array('escape' => false,'class'=>'btn btn-cons')); ?>

                </p>
            </fieldset>
            <?php echo $this->Form->end(); ?>
        </div>

        <div class="clr"></div>
    </div>
</div>

<script>
function showreset() {
    document.getElementById("advertisementimage").style.display = "block";
    document.getElementById("advertisementimage2").style.display = "block";
    document.getElementById("advertisementimage3").style.display = "block";
    document.getElementById("advertisementcode").style.display = "none";
}
function in_array(needle, haystack) {
    for (var i=0, j=haystack.length; i < j; i++) {
        if (needle == haystack[i])
            return true;
    }
    return false;
}

function getExt(filename) {
   var dot_pos = filename.lastIndexOf(".");
   if(dot_pos == -1)
      return "";
   return filename.substr(dot_pos+1).toLowerCase();
}



function imageValidation(){

      var filename = document.getElementById("BanneradvertisementImage").value;

        if(filename !=''){
            var filetype = ['jpeg', 'png', 'jpg', 'gif'];
            if(filename !=''){
                var ext = getExt(filename);
                ext = ext.toLowerCase();
                var checktype = in_array(ext, filetype);
                if(!checktype){
                   alert(ext+" file not allowed for advertisement image.");
                   return false;
                }
		else{
                    var fi = document.getElementById('BanneradvertisementImage');
                    var filesize = fi.files[0].size;//check uploaded file size
                    if(filesize > 2097152){
                        alert('Maximum 2 MB file size allowed for advertisement image.');
                        return false;
                    }
                }
            }
            return true;
        }
}

</script>

<script type="text/javascript">
  function show(value) {
	if(value=='code'){
    	document.getElementById('codeDiv').style.display="table-row";
    	document.getElementById('BanneradvertisementImage').style.display="none";
    }else{
    	document.getElementById('BanneradvertisementImage').style.display="table-row";
    	document.getElementById('codeDiv').style.display="none";
    }
}
</script>