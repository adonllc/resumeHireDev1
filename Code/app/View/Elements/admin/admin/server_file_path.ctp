
<?php global $server_file_path; if ($server_file_path) { ?>

    <div id="listingJS" style="display: none;" ></div>
    <div id="loaderID" style="display:none;position:absolute;margin-left:400px;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <div id='listID'>
       <?php echo $this->Form->create("User", array("url" => "index", "method" => "Post")); ?>
        <div class="grid simple vertical">
            <div class="grid-title no-border">
                <h4><span class="">List Server Configuration Path</span></h4>
                
            
            <div class="grid-body no-border" id="videowatermark_div"> 
                <div class="clr"></div>   



               
                <table class="table no-more-tables">
                    <thead>
                        <tr>
                            <th>Server Configuration Path</th>
                     <th>Directory Exists </th>
                     <th>Directory Permission</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($server_file_path as $server_file) { ?>    
                            <tr>
                                
								<td class="v-a-middle"><?php echo $server_file; ?></td>
                                <td class="v-a-middle"><?php 
                                if(file_exists($server_file)){
                                    echo $this->Html->image('test-pass-icon.png', array('title' => 'Exists'));
                                }else{
                                    echo $this->Html->image('cross-circle.png', array('title' => 'Does not Exists'));
                                }
                            ?>
                                </td>
                                <td class="v-a-middle"><?php echo substr(sprintf('%o', fileperms($server_file)), -4);
                            if (is_writable($server_file)) {
                                    echo ' (The directory is writable)';
                                } else {
                                    echo ' (The directory is not writable)';
                                } ?></td>
                                
                              									
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>				
                

                	

            </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
<?php
}?>

