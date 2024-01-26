<?php
if (isset($javascript)) {
    echo $javascript->link('tinymce/jscripts/tiny_mce/tiny_mce.js');
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="35"><table width="100%" cellspacing="0" cellpadding="0"
                               border="0">
                <tbody>
                    <tr>
                        <td width="8"><img width="8" height="32"
                                           src="<?php echo HTTP_PATH; ?>/img/tl-blue.png"></td>
                        <td class="breadcrumb">Administrator &gt; Add Page Content</td>
                        <td width="8"><img width="8" height="32"
                                           src="<?php echo HTTP_PATH; ?>/img/tr-blue.png"></td>
                    </tr>
                </tbody>
            </table></td>
    </tr>

    <tr>

        <?php echo $this->Session->flash(); ?>
    </tr>
    <tr>
        <td>
            <!-- Form Start -->
            <div id="middle-content">
                <div id="divMessageJS" style="display: none;"></div>				
                <?php echo $this->Form->create(NULL, array('url' => array('controller' => 'pages', 'action' => 'addPage/'), 'method' => 'POST', 'name' => 'frmCreatePage', 'enctype' => 'multipart/form-data')); ?>
                <table width="100%" border="0" cellspacing="3" cellpadding="3">
                    <tr>
                        <td colspan="2"><span class="require">* Please note that all
                                fields that have an asterisk (*) are required. </span></td>
                    </tr>
                    <tr>
                        <td>Title: <font color="red">*</font></td>
                        <td align="left"><?php echo $this->Form->text('Page.static_page_title', array('maxlength' => '254', 'size' => '20', 'label' => '', 'div' => false, 'class' => "form-inbox")) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Description : <font color="red">*</font></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td align="left"><?php echo $this->Fck->fckeditor(array('Page', 'static_page_description'), $this->Html->base, $this->data['Page']['static_page_description']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><?php echo $this->Form->input('Page.id', array('type' => 'hidden')); ?>
                            <?php echo $this->Form->submit('submitBtn.png', array('maxlength' => '50', 'size' => '30', 'label' => '', 'div' => false)) ?>
                        </td>
                    </tr>
                </table>
                <?php echo $this->Form->end(); ?>
            </div> <!-- Form End -->
        </td>
    </tr>

    <tr>

    </tr>
</table>

