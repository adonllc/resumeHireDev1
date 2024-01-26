<?php
                                        $coverCounter = 0;
                                        if ($this->data['CoverLetter']) {
                                            //pr($this->data['CoverLetter']);exit;
                                            foreach ($this->data['CoverLetter'] as $letter) {
                                                echo $this->Form->text('CoverLetter.' . $coverCounter . '.title', array('maxlength' => '30', 'class' => "required", 'placeholder' => 'Cover Letter Title', 'value' => $letter['CoverLetter']['title']));
                                                echo $this->Form->hidden('CoverLetter.' . $coverCounter . '.id', array('value' => $letter['CoverLetter']['id']));
                                                echo $this->Form->textarea('CoverLetter.' . $coverCounter . '.description', array('class' => "required", 'placeholder' => 'Cover Letter', 'value' => $letter['CoverLetter']['description']));
                                                $coverCounter++;
                                            
                                            ?>
                                                <span class="close_icon3_1">
                                            <?php echo $this->Ajax->link($this->Html->image('close.png', array('title' => __d('item', 'Delete', true))), array('controller' => 'candidates', 'action' => 'deleteCover', $letter['CoverLetter']['id']), array('escape' => false, 'update' => 'MediaGroup', 'indicator' => 'loaderID', 'class' => 'custom_link', 'confirm' =>'Are you sure ?')); ?>
                                        </span>
                                                <?php }
                                        }
                                        ?>