<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
?> 
<div style="margin:0 auto; width:700px; padding:10px 0 20px 0; background:#efefef;">
    <span style="margin:25px 10px 0 32px;">
        <a href="<?php echo HTTP_PATH; ?>">
            <img align="absmiddle" style="margin-right: 20px;" alt="<?php echo $site_title; ?>" src="<?php echo HTTP_PATH; ?>/app/webroot/img/admin_logo.png"/>
        </a>
    </span>
    <h1 style="color:#4D4D4D; padding:0 0 0 32px; font:normal 30px Arial, Helvetica, sans-serif;">Enquiry on <?php echo $site_title; ?></h1>
    <div style="padding:20px; margin:10px 35px; background:#FFF;">
        <h2 style="color:#1D7BCF; font-weight:26px; font:bold 23px Arial, Helvetica, sans-serif;">Dear Admin </h2>

        <p style="color:#4D4D4D; font:normal 16px Arial, Helvetica, sans-serif;">
            An user on <?php echo $site_title; ?> has sent an enquiry.Below are the details.
        </p>
        <p style="color:#4D4D4D; font:normal 16px Arial, Helvetica, sans-serif;">
        <p>
            <strong>Name:</strong> <?php echo $user['User']['user_name']; ?><br />
        </p>
        <p>
            <strong>Email:</strong> <?php echo $user['User']['email']; ?><br />
        </p>
        <p>
            <strong>Country:</strong> <?php echo $user['User']['country']; ?><br />
        </p>
        <p>
            <strong>Subject:</strong> <?php echo $user['User']['subject']; ?><br />
        </p>
        <p>
            <strong>Message:</strong> <?php echo $user['User']['message']; ?><br />
        </p>
        </p>


    </div>
</div>
<?php //exit; ?>
