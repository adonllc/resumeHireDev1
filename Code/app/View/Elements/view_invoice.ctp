<?php
$logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));
// pr($logoImage);
if (isset($logoImage) && !empty($logoImage)) {
    $logo = DISPLAY_FULL_WEBSITE_LOGO_PATH . $logoImage;
} else {
    $logo = ' ';
}

$this->Html->image($logo, array('alt' => $site_title, 'title' => $site_title))
?>
<table  style="width:700px; font-size:14px; font-family:Open Sans">

    <tr> 
        <td style="line-height: 45px;text-align: center; font-weight: bold;font-size: 18px; width: 550px" colspan="2"><?php echo strtoupper(__d('user', 'Invoice', true)); ?></td>
    </tr>


    <tr>
        <td style="height: auto;width: 100px">
            <?php
            echo $this->Html->image($logo, array('alt' => $site_title, 'title' => $site_title));
            ?>
        </td>
<!--        <td style="text-align: right;font-weight: bold; font-size: 15px; width: 400px" >
            <?php // echo __d('user', 'You payment must be sent to', true); ?>: <br/>
            KOUAR INVESTMENT<br/>
            9 rue Anatole de la Forge 75017 Paris
        </td>-->

    </tr>
    <tr>
        <td colspan="2" style="text-align: left; height: 20px;" >

        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left" >
            <b><?php echo __d('user', 'First Name', true); ?> :</b> <?php echo $planDetail['User']['first_name']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">
            <b><?php echo __d('user', 'Last Name', true); ?> :</b> <?php echo $planDetail['User']['last_name']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">
            <b><?php echo __d('user', 'Contact Number', true); ?> :</b> <?php echo $this->Text->usformat($planDetail['User']['contact']); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">
            <b><?php echo __d('user', 'Email Address', true); ?> :</b> <?php echo $planDetail['User']['email_address']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">
            <b><?php echo __d('user', 'Company Name', true); ?> :</b> <?php echo $planDetail['User']['company_name']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">
            <b><?php echo __d('user', 'Address', true); ?> :</b> <?php echo $planDetail['User']['address']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left; height: 20px;" >

        </td>
    </tr>
    <tr>
        <td style="width: 520px;">
            <table  style="width: 100%;text-align: right">
                <tr> <td style="width: 30%">
                    </td>
                    <td style="width: 70%;text-align: left;font-weight: bold;height: 30px; line-height: 30px;">
                        <span style="width: 70%;background-color: #2e79f2">&nbsp;&nbsp;<?php echo strtoupper(__d('user', 'Invoice No', true)); ?> : <?php echo str_pad($planDetail['UserPlan']['invoice_no'], 4, '0', STR_PAD_LEFT); ?> <?php echo strtoupper(__d('user', 'of', true)); ?> <?php echo date('d/m/Y', strtotime($planDetail['UserPlan']['start_date'])); ?>&nbsp;&nbsp;</span>  &nbsp;&nbsp;</td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td colspan="2" style="text-align: left; height: 20px;" >

        </td>
    </tr>

    <tr>
        <td colspan="2"  style="text-align: center;font-weight: bold;height: 50px;width: 550px;">
            <?php echo strtoupper(__d('user', 'Plan', true) . ' ' . $planDetail['Plan']['plan_name']); ?>
        </td>
    </tr>
    <?php
    $plan_id = $planDetail['Plan']['id'];
//    $plan_name = "PREMIUM";
    $plan_name = $planDetail['Plan']['plan_name'];
    global $planType;
    global $planFeatuersMax;
    global $planFeatuers;

    global $planFeatuersDis;
    global $planFeatuersHelpText;
    $fvalues = json_decode($planDetail['UserPlan']['fvalues'], true);
    $featureIds = explode(',', $planDetail['UserPlan']['features_ids']);
    $AccessCandidateSearching = 0;
    $joncnt = '';
    $ddd = '';
    if ($featureIds) {
        foreach ($featureIds as $fid) {
            $ddd = '<tr><td colspan="2" style="text-align: left" >'.$this->Html->image(HTTP_IMAGE . '/front/check.png');
            if (array_key_exists($fid, $fvalues)) {
                if ($fvalues[$fid] == $planFeatuersMax[$fid]) {
                    $joncnt = 'Unlimited';
                    $ddd .= '<b> Unlimited</b>';
                } else {
                    $joncnt = $fvalues[$fid];
                    $ddd .= '<b> ' . $fvalues[$fid] . '</b>';
                }
            }

            if (array_key_exists($fid, $planFeatuersHelpText)) {
                $timecnt = $planDetail['Plan']['type_value'] . ' ' . $planDetail['Plan']['type'];
                if ($fid == 1) {
                    $farray = array('[!JOBS!]', '[!TIME!]', '[!RESUME!]');
                    $toarray = array($joncnt, $timecnt, '');
                } elseif ($fid == 2) {
                    $farray = array('[!JOBS!]', '[!TIME!]', '[!RESUME!]');
                    $toarray = array('', $timecnt, $joncnt);
                }

                $msgText = str_replace($farray, $toarray, $planFeatuersHelpText[$fid]);
//                $disText = '<div class="help_bxse"><i class="fa fa-info-circle" aria-hidden="true"></i><div class="uxicon_help">' . $msgText . '</div></div>';
                $disText = '';
            } else {
                $disText = '';
            }
            $ddd .= ' ' . $planFeatuersDis[$fid] . $disText . '</td></tr>';
            echo $ddd;
        }
    }
    ?>



    <tr>
        <td colspan="2" style="text-align: left; height: 20px;" >

        </td>
    </tr>


    <tr>
        <td style="width: 400px; text-align: right;font-weight: bold;"><?php echo strtoupper(__d('user', 'Total', true)); ?></td>
        <td style="text-align: center; width: 100px;"><?php echo $planDetail['UserPlan']['amount'] . ' ' . CURRENCY; ?></td>
    </tr>





</table>
