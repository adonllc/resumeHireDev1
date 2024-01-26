<table border="0" style="width:600px; font-size:8px; font-family:Open Sans">
    <tbody>
        <tr style="width:100%;">
            <td style="width:10px;">&nbsp;</td>
            <td style="width:560px;">
                <table style="width:90%;">
                    <tr> <td style="line-height: 35px; width: 100%; text-align: center; font-size: 18px;" colspan="2"><?php echo __d('user', 'Curriculum Vitae (CV)', true); ?> </td></tr>
                </table>
                <table style="width:90%;">
                    <tr> <td style="line-height: 35px;" colspan="2"> </td></tr>
                    <tr>
                        <td>
                            <table>

                                <tr>
                                    <td style="font-size: 10px;font-weight: bold;"><?php echo $userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 10px;"><?php echo $userdetail['User']['email_address']; ?></td>
                                </tr>
                                <?php if ($userdetail['User']['contact']) { ?>
                                    <tr>
                                        <td style="font-size: 10px;"><?php echo $this->Text->usformat($userdetail['User']['contact']); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($userdetail['User']['profile_image'] != "") { ?>

                                    <?php if ($userdetail['User']['location']) { ?>

                                        <tr>
                                            <td style="font-size: 10px;"><?php echo __d('user', 'Address', true); ?>: <?php echo $userdetail['User']['location']; ?></td>
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
                                        <?php echo __d('user', 'Address', true); ?>: <?php
                                        echo $userdetail['User']['location'];
                                    }
                                    ?></td>
                                <?php
                            }
                        }
                        ?>

                    </tr>
                    <tr> <td style="line-height: 25px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> <?php echo __d('user', 'Education Qualification', true);?></td></tr>
                    <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
                    <?php if (isset($userdetail['Education']) && !empty($userdetail['Education'])) { ?>
                        <?php
                        foreach ($userdetail['Education'] as $education) {
                            $couses[] = $education['basic_course_id'];
                            ?><tr><td style="line-height: 15px;font-size: 9px;" colspan="2">• <?php echo __d('user', 'I have Passed', true);?> <?php echo $courseName = ClassRegistry::init('Course')->field('name', array('Course.id' => $education['basic_course_id'])); ?> <?php echo __d('user', 'in', true);?> <?php
                            if (isset($education["basic_year"])) {
                                echo $education["basic_year"];
                            } else {
                                echo'N/A';
                            }
                            ?>
                                    in <?php echo $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id'])); ?> <?php echo __d('user', 'from', true);?> <?php
                                    if (isset($education["basic_university"])) {
                                        echo $education["basic_university"];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>.</td></tr>
                            <?php
                        }
                        ?>




                    <?php }
                    ?>

                    <tr> <td style="line-height: 25px;font-size: 12px;" colspan="2"> </td></tr>
                    <tr> <td style="line-height: 24px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> <?php echo __d('user', 'Experience', true);?></td></tr>
                    <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
                    <?php if (isset($userdetail['Experience']) && !empty($userdetail['Experience'])) { ?>

                        <?php
                        foreach ($userdetail['Experience'] as $experience) {
                            ?><tr><td style="line-height: 15px;font-size: 9px;" colspan="2">• <?php echo __d('user', 'I have worked as a', true);?> <?php
                            if (isset($experience["role"])) {
                                echo $experience['role'];
                            } else {
                                echo'N/A';
                            }
                            ?> <?php
                            if (isset($experience["designation"])) {
                                echo $experience['designation'];
                            } else {
                                echo'N/A';
                            }
                            ?> <?php echo __d('user', 'for', true);?> <?php echo $experience['company_name']; ?> <?php echo __d('user', 'since', true);?> <?php
                                    if (isset($experience["from_month"]) && isset($experience["from_year"]) && isset($experience["to_month"]) && isset($experience["to_year"])) {

                                        $experience['from_month'] == 1;
                                        switch ($experience['from_month']) {
                                            case "1":
                                                $fromName = 'Jan';
                                                break;
                                            case "2":
                                                $fromName = 'Feb';
                                                break;
                                            case "3":
                                                $fromName = 'Mar';
                                                break;
                                            case "4":
                                                $fromName = 'Apr';
                                                break;
                                            case "5":
                                                $fromName = 'May';
                                                break;
                                            case "6":
                                                $fromName = 'June';
                                                break;
                                            case "7":
                                                $fromName = 'Jul';
                                                break;
                                            case "8":
                                                $fromName = 'Aug';
                                                break;
                                            case "9":
                                                $fromName = 'Sept';
                                                break;
                                            case "10":
                                                $fromName = 'Oct';
                                                break;
                                            case "11":
                                                $fromName = 'Nov';
                                                break;
                                            case "12":
                                                $fromName = 'Dec';
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

                                        echo $fromName . '-' . $experience['from_year'] . ' ' . __d('common', 'to', true) . ' ' . $toName . '-' . $experience['to_year'];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>

                                    <br>
                                    <table>
                                        <?php if ($experience['industry'] != "") {
                                            ?>  <tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold"><?php echo __d('user', 'Industry', true);?>: </label> <?php echo $experience['industry']; ?> </td></tr><?php }
                                        ?>
                                        <?php if ($experience['functional_area'] != "") {
                                            ?>  <tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold"><?php echo __d('user', 'Functional area', true);?>: </label> <?php echo $experience['functional_area']; ?>  </td></tr><?php }
                                        ?>
                                        <?php if ($experience['role'] != "") {
                                            ?>  <tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold"><?php echo __d('user', 'Role', true);?>: </label> <?php echo $experience['role']; ?>  </td></tr><?php }
                                        ?>
                                        <?php if ($experience['job_profile'] != "") {
                                            ?>  <tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold"><?php echo __d('user', 'Job Profile', true);?>: </label> <?php echo $experience['job_profile']; ?>  </td></tr><?php }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                            <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
                            <?php
                        }
                        ?>                



                        <?php ?>

                    <?php }
                    ?>
                    <tr> <td style="line-height: 25px;font-size: 12px;" colspan="2"> </td></tr>
                    <tr><td><label style="font-weight: bold; font-size: 11px; text-align: left;"><?php echo __d('user', 'Date', true);?>: <?php echo date('m/d/Y'); ?></label></td><td><label style="text-align: right;font-size: 11px;font-weight: bold"><?php echo __d('user', 'Signature', true);?></label></td></tr>


                </table>
            </td>
        </tr>
    </tbody>
</table>
