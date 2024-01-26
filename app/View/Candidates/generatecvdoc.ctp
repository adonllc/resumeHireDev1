<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=document_name.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
echo "<label style='font-weight: bold'>" . __d('user', 'Name and surname', true) . "</label> : " . ucwords($userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name']);


echo "</body>";
echo "</html>";
die;
?>
<table style="width:90%;">
    <tr> <td style="line-height: 35px;" colspan="2"> </td></tr>
    <tr>
        <td>
            <table>

                <tr>
                    <td style="font-size: 10px;"><label style="font-weight: bold"><?php echo __d('user', 'Name and surname', true); ?>:</label> <?php echo $userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 10px;"><label style="font-weight: bold"><?php echo __d('user', 'Email Address', true); ?>:</label> <?php echo $userdetail['User']['email_address']; ?></td>
                </tr>
                <?php if ($userdetail['User']['contact']) { ?>
                    <tr>
                        <td style="font-size: 10px;"><label style="font-weight: bold"><?php echo __d('user', 'Phone Number', true); ?>:</label> <?php echo $this->Text->usformat($userdetail['User']['contact']); ?></td>
                    </tr>
                <?php } ?>
                <?php if ($userdetail['User']['profile_image'] != "") { ?>

                    <?php if ($userdetail['User']['location']) { ?>

                        <tr>
                            <td style="font-size: 10px;"><label style="font-weight: bold"><?php echo __d('user', 'Address', true); ?>:</label> <?php echo $userdetail['User']['location']; ?></td>
                        </tr>

                        <?php
                    }
                }
                ?>
            </table>
        </td>


        <?php if ($userdetail['User']['profile_image'] == "") { ?>




            <?php if ($userdetail['User']['location']) { ?>

                <td style="text-align: right;font-size: 10px;"><?php echo __d('user', 'Address', true); ?>: <?php echo $userdetail['User']['location']; ?></td>
            <?php } ?>
            <?php
        } else {


            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'];
            if (file_exists($path) && !empty($userdetail['User']['profile_image'])) {
                ?><td style="text-align: right;"><?php echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'], array('escape' => false, 'rel' => 'nofollow', 'style' => 'width:80px;height:80px;'), array('class' => ' ')); ?></td><?php
            } else {
                ?><td style="text-align: right;">
                <?php if ($userdetail['User']['location']) { ?>
                        Address: <?php
                        echo $userdetail['User']['location'];
                    }
                    ?></td>
                <?php
            }
        }
        ?>

    </tr>

    <tr> <td style="line-height: 24px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> <?php echo strtoupper(__d('user', 'Experience', true)); ?></td></tr>
    <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
    <?php
    if (isset($userdetail['Experience']) && !empty($userdetail['Experience'])) {
        $total_records = count($userdetail['Experience']);
        foreach ($userdetail['Experience'] as $key => $experience) {
            ?>
                    <!--<table>-->
            <tr>
                <td style="line-height: 15px;font-size: 9px;" colspan="2"> 
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    if (isset($experience["from_month"]) && isset($experience["from_year"]) && isset($experience["to_month"]) && isset($experience["to_year"])) {

                        $experience['from_month'] == 1;
                        switch ($experience['from_month']) {
                            case "1":
                                $fromName = __d('user', 'January', true);
                                break;
                            case "2":
                                $fromName = __d('user', 'Febuary', true);
                                break;
                            case "3":
                                $fromName = __d('user', 'March', true);
                                break;
                            case "4":
                                $fromName = __d('user', 'April', true);
                                break;
                            case "5":
                                $fromName = __d('user', 'May', true);
                                break;
                            case "6":
                                $fromName = __d('user', 'June', true);
                                break;
                            case "7":
                                $fromName = __d('user', 'July', true);
                                break;
                            case "8":
                                $fromName = __d('user', 'August', true);
                                break;
                            case "9":
                                $fromName = __d('user', 'September', true);
                                break;
                            case "10":
                                $fromName = __d('user', 'October', true);
                                break;
                            case "11":
                                $fromName = __d('user', 'November', true);
                                break;
                            case "12":
                                $fromName = __d('user', 'Decemeber', true);
                                break;
                            default:
                                $fromName = 'N/A';
                        }

                        $experience['to_month'] == 1;
                        switch ($experience['to_month']) {
                            case "1":
                                $toName = __d('user', 'January', true);
                                break;
                            case "2":
                                $toName = __d('user', 'Febuary', true);
                                break;
                            case "3":
                                $toName = __d('user', 'March', true);
                                break;
                            case "4":
                                $toName = __d('user', 'April', true);
                                break;
                            case "5":
                                $toName = __d('user', 'May', true);
                                break;
                            case "6":
                                $toName = __d('user', 'June', true);
                                break;
                            case "7":
                                $toName = __d('user', 'July', true);
                                break;
                            case "8":
                                $toName = __d('user', 'August', true);
                                break;
                            case "9":
                                $toName = __d('user', 'September', true);
                                break;
                            case "10":
                                $toName = __d('user', 'October', true);
                                break;
                            case "11":
                                $toName = __d('user', 'November', true);
                                break;
                            case "12":
                                $toName = __d('user', 'Decemeber', true);
                                break;
                            default:
                                $toName = 'N/A';
                        }

                        echo $fromName . '-' . $experience['from_year'] . ' ' . __d('user', 'to', true) . ' ' . $toName . '-' . $experience['to_year'] . ' - ' . $experience['company_name'];
                    } else {
                        echo'N/A';
                    }
                    ?>

                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                   <!--<table>-->

                    <?php if ($experience['role'] != "") {
                        ?> <?php echo $experience['role']; ?>
                        <br><?php }
                    ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if ($experience['job_profile'] != "") {
                        ?> <?php echo $experience['job_profile']; ?> <?php }
                    ?>
                    <!--</table>-->
                </td>
            </tr>
            <!--</table>-->
            <?php if (($key + 1) != $total_records) { ?>
                <tr> <td style="line-height: 10px;font-size: 12px;" colspan="2"> </td></tr>                            
                <?php
            }
        }
        ?>                

        <?php ?>

    <?php }
    ?>


    <tr> <td style="line-height: 25px;font-size: 12px;" colspan="2"> </td></tr>

    <tr> <td style="line-height: 25px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> <?php echo strtoupper(__d('user', 'Education Specialization', true)); ?></td></tr>
    <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
    <?php if (isset($userdetail['Education']) && !empty($userdetail['Education'])) { ?>
        <?php
        $total_records = count($userdetail['Education']);
        foreach ($userdetail['Education'] as $key => $education) {
            $couses[] = $education['basic_course_id'];
            ?>
            <tr>
                <td style="line-height: 15px;font-size: 9px;" colspan="2">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    if (isset($education["basic_year"])) {
                        echo $education["basic_year"];
                    } else {
                        echo'N/A';
                    }
                    echo ' - ';
                    if (isset($education["basic_university"])) {
                        echo $education["basic_university"];
                    } else {
                        echo'N/A';
                    }
                    ?>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <?php echo $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id'])); ?> <?php // echo __d('user', 'from', true);?> 
                </td></tr>             
            <?php if (($key + 1) != $total_records) { ?>
                <tr> <td style="line-height: 10px;font-size: 12px;" colspan="2"> </td></tr>                         
                <?php
            }
        }
        ?>




    <?php }
    ?>
    <tr> <td style="line-height: 25px;font-size: 12px;" colspan="2"> </td></tr>
    <tr> <td style="line-height: 25px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> <?php echo strtoupper(__d('user', 'Skills', true)); ?></td></tr>
    <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
    <?php
    if (isset($userdetail['User']['skills']) && !empty($userdetail['User']['skills'])) {
        $experiences = explode(',', $userdetail['User']['skills']);
        $total_records = count($experiences);
        foreach ($experiences as $key => $experience) {
            ?>
            <tr>
                <!--<td style="width:15px;">&nbsp;</td>-->
                <td style="line-height: 15px;font-size: 9px;" colspan="2">
                    &nbsp;&nbsp;&nbsp;&nbsp;• <?php
                    echo $experience;
                    ?>
                </td>
            </tr>

            <?php
        }
        ?>                

        <?php ?>

    <?php }
    ?>




</table>