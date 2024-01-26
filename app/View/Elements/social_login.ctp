
<script type="text/javascript">

    var newwindow;
    var intId;
    function fblogin() {
        //  alert('hi');

        var screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
                screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
                outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
                outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
                width = 800,
                height = 500,
                left = parseInt(screenX + ((outerWidth - width) / 2), 10),
                top = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                features = (
                        'width=' + width +
                        ',height=' + height +
                        ',left=' + left +
                        ',top=' + top
                        );

        var URL = '<?php echo HTTP_PATH; ?>/users/chklogin/';
        // var usetype = $('#UserUserType').val();
        var usetype = '<?php echo $userType; ?>';


        // alert(usetype); 
        var usetypeP = '';
        if (usetype) {
            usetypeP = usetype;
        }

        // var loginurl = "<?php //echo $loginUrl;                 ?>";
        //var loginurlarr = loginurl.split("&state");
        // var updateloginurl = loginurlarr[0] + "%2F" + usetypeP + "&state" + loginurlarr[1];
        // var updateloginurlarr = updateloginurl.split("&cancel_url");
        // var newloginurl = updateloginurlarr[0] + "%2F" + usetypeP + "&cancel_url" + updateloginurlarr[1];
        //newwindow = window.open(newloginurl, 'Signup by facebook', features);
        newwindow = window.open('<?php echo HTTP_PATH; ?>/app/webroot/facebook/fbconfig.php?usetype=' + usetype, 'Signup by facebook', features);
        //        newwindow = window.open(URL+usetypeP, 'Signup by facebook', features);

        if (window.focus) {
            newwindow.focus()
        }
        return false;

    }


//    function setSession(filename) {
////        var filename = document.getElementById("UserUserType").value; 
////        var filename = document.getElementById("UserUserType").value; 
////        alert(filename);
//        if (filename != '') {
//            $.ajax({
//                type: 'POST',
//                url: "<?php //echo HTTP_PATH;       ?>/users/gmailSession/" + filename,
//                cache: false,
//                success: function (result) {
//
//                },
//                error: function () {
//                    console.log('<?php //echo __d('view', 'there was a problem checking the fields', true);       ?>');
//                }
//            });
//        }
//    }


</script>



<div class="soc">
    <?php
//    if ($type == 'jobseeker') {
//        echo $this->Html->link($this->Html->image('front/fb_sign_login.png', array('alt' => "Facebook Login", 'title' => 'Facebook Login')), 'javascript:void(0);', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "fblogin();return false;"));
//    }
    ?>

    <?php if ($checkpage == 'login') { ?>
        <div class="social_login_with">
            <ul>
                <li>
                    <?php
                    //if ($type == 'jobseeker') {
                    //echo $this->Html->link('<i class="fa fa-facebook" aria-hidden="true"></i>','javascript:void(0)', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "fblogin();return false;"));
                    echo $this->Html->link('<i class="fa fa-facebook" aria-hidden="true"></i> '.__d('user', 'Signin with facebook', true), 'javascript:void(0)', array('class' => 'fbicon', 'escape' => false, 'rel' => 'nofollow', 'onclick' => "fblogin();return false;"));
                    //}
                    ?>
                </li>
                <li>
                    <?php //echo $this->Html->link('<i class="fa fa-google-plus" aria-hidden="true"></i>','javascript:void(0)', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "connect_with_gmail();return false;")); ?>
                    <?php echo $this->Html->link('<i class="fa fa-google-plus" aria-hidden="true"></i> '.__d('user', 'Signin with google', true), 'javascript:void(0)', array('class' => 'gplusicon', 'escape' => false, 'rel' => 'nofollow', 'onclick' => "connect_with_gmail();return false;")); ?>
                </li>
                <li>
                    <?php //echo $this->Html->link('<i class="fa fa-linkedin" aria-hidden="true"></i>','javascript:void(0)', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "connect_with_gmail();return false;")); ?>
                    <?php echo $this->Html->link('<i class="fa fa-linkedin" aria-hidden="true"></i> '.__d('user', 'Signin With linkedin', true), 'javascript:void(0);', array('class' => 'new_connect_link linkedinicon', 'rel' => 'nofollow', 'escape' => false, 'onclick' => "connect_with_linkedin();return false;")); ?>
                </li>
            </ul>                                
        </div>
    <?php } else { ?>
        <div class="social_login_with">
            <ul>
                <li>
                    <?php
                    //if ($type == 'jobseeker') {
                    //echo $this->Html->link('<i class="fa fa-facebook" aria-hidden="true"></i>','javascript:void(0)', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "fblogin();return false;"));
                    echo $this->Html->link('<i class="fa fa-facebook" aria-hidden="true"></i> '.__d('user', 'Signin with facebook', true), 'javascript:void(0)', array('class' => 'fbicon', 'escape' => false, 'rel' => 'nofollow', 'onclick' => "fblogin();return false;"));
                    //}
                    ?>
                </li>
                <li>
                    <?php //echo $this->Html->link('<i class="fa fa-google-plus" aria-hidden="true"></i>','javascript:void(0)', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "connect_with_gmail();return false;")); ?>
                    <?php echo $this->Html->link('<i class="fa fa-google-plus" aria-hidden="true"></i> '.__d('user', 'Signin with google', true), 'javascript:void(0)', array('class' => 'gplusicon', 'escape' => false, 'rel' => 'nofollow', 'onclick' => "connect_with_gmail();return false;")); ?>
                </li>
                <li>
                    <?php //echo $this->Html->link('<i class="fa fa-linkedin" aria-hidden="true"></i>','javascript:void(0)', array('class' => '', 'escape' => false,'rel'=>'nofollow', 'onclick' => "connect_with_gmail();return false;")); ?>
                    <?php echo $this->Html->link('<i class="fa fa-linkedin" aria-hidden="true"></i> '.__d('user', 'Signin With linkedin', true), 'javascript:void(0);', array('class' => 'new_connect_link linkedinicon', 'rel' => 'nofollow', 'escape' => false, 'onclick' => "connect_with_linkedin();return false;")); ?>
                </li>
            </ul>                                
        </div>
    <?php } ?>

</div>
<!--<div class="orrrr"><span>OR</span></div>-->

<script type="text/javascript">

    var newwindow;
    var intId;
    function connect_with_gmail() {
        //var filename = document.getElementById("UserUserType").value;
        var filename = 'jobseeker';
        if (filename != '') {
                    var screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
                            screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
                            outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
                            outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
                            width = 530,
                            height = 470,
                            left = parseInt(screenX + ((outerWidth - width) / 2), 10),
                            top = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                            features = (
                                    'width=' + width +
                                    ',height=' + height +
                                    ',left=' + left +
                                    ',top=' + top
                                    );
                    newwindow = window.open('<?php echo 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ?>', 'Login_by_Gmail', features);
                    if (window.focus) {
                        newwindow.focus()
                    }
                    return false;
        }

//        } else {
//            alert('Please select in which account you want to sign Up as?');
//            return false;
//        }
    }



    /*************lINKEDIN***************/

    var newwindow;
    var intId;
    function connect_with_linkedin() {
        //var userType = getCheckedRadioValue('data[User][user_type]');
        //var userType = getCheckedRadioValue('jobseeker');
        var userType = '<?php echo $userType; ?>';
        // if (userType == 'Candidate' || userType == 'Partner') {
        // alert('LinkedIn Registration not available for Partner/Candidate Users');
        // } else {
    
                var screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
                        screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
                        outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
                        outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
                        width = 530,
                        height = 470,
                        left = parseInt(screenX + ((outerWidth - width) / 2), 10),
                        top = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                        features = (
                                'width=' + width +
                                ',height=' + height +
                                ',left=' + left +
                                ',top=' + top
                                );
                newwindow = window.open('<?= HTTP_PATH . '/users/linkedinlogin' ?>', 'Login_by_Linkedin', features);
                if (window.focus) {
                    newwindow.focus()
                }
                return false;
            

        //var userType = getCheckedRadioValue('data[User][user_type]');

        //}
    }

    function getCheckedRadioValue(name) {
        var elements = document.getElementsByName(name);
        for (var i = 0, len = elements.length; i < len; ++i) {
            if (elements[i].checked) {
                return elements[i].value;
            }
        }
    }

</script>


