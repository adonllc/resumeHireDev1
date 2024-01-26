
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-crosshairs" aria-hidden="true"></i> Change Color Theme', 'javascript:void(0);', array('escape' => false));
?>
<style>
    .sp-container{
        background-color:rgb(252, 229, 205);   }

</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("#changeusername").validate();
        // $('.cp5').colorpicker();
        <?php 
            $data = ClassRegistry::init('Changecolors')->find('first',array('conditions'=>array('Changecolors.id'=>6))); 
            // print_r($data);exit;
        ?>
            var bgcolor = '<?php echo $data['Changecolors']['theme_background'];  ?>';
            var themecolor = '<?php echo $data['Changecolors']['theme_color'];  ?>';
            var disabled_opt = parseInt('<?php echo $data['Changecolors']['is_default'];?>');
            var disabled = '';
            // console.log(typeof(disabled_opt));
            if(disabled_opt==1){
                disabled = true;
                $('#default_color').prop('checked', true);
                $('#togglePaletteOnly1').attr('title','Default color is selected, to choose your color uncheck Set Default Color ');
                $('#togglePaletteOnly2').attr('title','Default color is selected, to choose your color uncheck Set Default Color');
            }else{
                disabled = false;
                $('#default_color').prop('checked', false);
            }
            // console.log(typeof(disabled_opt));
            init_spectrum(themecolor,bgcolor,disabled);
            $(document).on('click','#default_color',function(){
                if($('#default_color').is(':checked')){
                    init_spectrum('#33b6cb','#f15424',true);
                    $('#togglePaletteOnly2').val('#f15424');
                    $('#togglePaletteOnly1').val('#33b6cb');
                }else{
                    // alert('true');
                    init_spectrum('#33b6cb','#f15424',false);
                    $('#togglePaletteOnly2').val('#f15424');
                    $('#togglePaletteOnly1').val('#33b6cb');
                }
            });
         $(document).on('click','#save_btn',function(e){
            e.preventDefault();
            $("#togglePaletteOnly1").spectrum("enable");
            $("#togglePaletteOnly2").spectrum("enable");
            // init_spectrum('#f15424','#33b6cb',false);
            $('#changecolorscheme').submit();
         });
         //t.toHsvString()
    });
    function init_spectrum(themecolor,bgcolor,disabled){

        $("#togglePaletteOnly1").spectrum({
            showPaletteOnly: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: 'more',
            togglePaletteLessText: 'less',
            color: themecolor,
            change: function(color) {
                // color.toHsvString(); // #ff0000
                color.toHexString();
            },
            palette: [
                ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
            ]
        });
        $("#togglePaletteOnly2").spectrum({
            showPaletteOnly: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: 'more',
            togglePaletteLessText: 'less',
            color: bgcolor,
            change: function(color) {
                // color.toHsvString(); // #ff0000
                color.toHexString();
            },
            palette: [
                ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
            ]
        });
        if(disabled){ 
            $("#togglePaletteOnly1").spectrum("disable");
            $("#togglePaletteOnly2").spectrum("disable");
        }else{
            $("#togglePaletteOnly1").spectrum("enable");
            $("#togglePaletteOnly2").spectrum("enable");
       
        }
    }
</script>
<?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'changecolorscheme', 'enctype' => 'multipart/form-data', 'id' => 'changecolorscheme', 'class' => 'form-horizontal tasi-form')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <?php echo $this->Session->flash(); ?>
                <!-- <h4 style="margin-left:15px" class="m-bot15">Change Admin Username</h4> -->
                <section class="panel">
                    <div class="panel-body">                              
                        <div class="form-group">
                            <label class="col-sm-4 col-sm-4 control-label">Theme Color</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->text('Changecolors.theme_color', array('placeholder' => "Color Code", 'label' => '', 'div' => false, 'class' => 'form-control required cp5 ','id'=>'togglePaletteOnly1', 'data-bv-notempty' => 'true')) ?>
                            </div>
                                <!-- <input type="text" name="button_color" class="form-control required cp5" /> -->
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-sm-4 control-label">Theme Background Color</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->text('Changecolors.theme_background', array('placeholder' => "Color Code", 'label' => '', 'div' => false, 'class' => 'form-control required cp5 ','id'=>'togglePaletteOnly2', 'data-bv-notempty' => 'true')) ?>
                            </div>
                            
                                <!-- <input type="text" name="button_color" class="form-control required cp5" /> -->
                            <?php echo $this->Form->input('Changecolors.id', array('type' => 'hidden'));
                                  ?>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-4 col-sm-4 control-label">Set Default Color</label>
                                <div class="col-sm-8">
                                    <div class="des_box_check">
                                        <input name="data[Changecolors][is_default]" type="checkbox" id="default_color" value="1" >
                                    </div>
                                </div>
                        </div>      
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                    <?php 
                                    // if(IS_LIVE){
                                        echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success','id'=>'save_btn')); 
                                        echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); 
                                    // }else{
                                        // echo "<blockquote> You are not allowed to update above information, because It's a demo of this product. Once we deliver code to you, you'll be able to update Configurations. </blockquote>";
                                    // }
                                    ?>
                            </div>
                        </div>
                    </div>
                </section>                          
            </div>
        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>
