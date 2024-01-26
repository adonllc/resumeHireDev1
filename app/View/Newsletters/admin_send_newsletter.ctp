<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">

    $(document).ready(function () {
        $("#employer_user").show();
        $("#jobseeker_user").hide();
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s`~!@#$%^&*()+={}|;:'",.\/?\\-]+$/.test(value);
        }, "Please do not enter  special character like < or >");
        $("#NewsletterFrom").validate();


    });

    function selectuser(userval) {
        //alert(userval);
        if (userval == 'recruiter') {
            $("#employer_user").show();
            $("#jobseeker_user").hide();

        } else if (userval == 'candidate') {
            $("#employer_user").hide();
            $("#jobseeker_user").show();
        } else {
            $("#employer_user").hide();
            $("#jobseeker_user").hide();
        }

    }


    function show_newsletter(value) {

        if (value == '2') {
            document.getElementById('Newsletter').style.display = "block";
        } else {
            document.getElementById('Newsletter').style.display = "none";
        }
    }

    function show_newsletter1(value) {

        if (value == '2') {
            document.getElementById('Newsletter1').style.display = "block";
        } else {
            document.getElementById('Newsletter1').style.display = "none";
        }
    }

</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-users" ></i> Newsletter » ', array('controller' => 'newsletters', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Send Newsletter to Newsletter Subscribers', 'javascript:void(0)', array('escape' => false));
?>

<?php echo $this->Form->create('NewsletterFrom', array('method' => 'POST', 'name' => 'addnewsletter', 'enctype' => 'multipart/form-data', 'id' => 'NewsletterFrom')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Send Newsletter to Newsletter Subscribers </h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Send Newsletter to Newsletter Subscribers:
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Select Template <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->select('Sendmail.template_id', $templates, array('empty' => 'Select Newsletter', 'label' => '', 'div' => false, 'class' => "form-control required", 'id' => 'category_id')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Select User Type <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <table cellpadding="5">
                                    <tr>
                                        <td><input id="subscriber" type="radio" name="usertype" value="recruiter" onchange="selectuser(this.value)" class="required" checked></td>
                                        <td>Employer</td>
                                    </tr>
                                    <tr>
                                        <td valign="top"> <input id="register" type="radio" name="usertype" value="candidate" onchange="selectuser(this.value);" class="required" ></td>
                                        <td>Jobseeker</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group" id="employer_user">
                            <label class="col-sm-2 col-sm-2 control-label">Select Email <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <table cellpadding="5">
                                    <tr>
                                        <td><input id="all" type="radio" name="data[Sendmail][empstatus]" value="1" onchange="show_newsletter(this.value);" checked></td>
                                        <td>Send mail to all Employer</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <input id="sendTo" type="radio" name="data[Sendmail][empstatus]" value="2" onchange="show_newsletter(this.value);">
                                        </td>
                                        <td>Select Employer to send a newsletter</td>
                                    </tr>
                                    <tr>
                                        <td valign="top" colspan="2"> 
                                            <?php echo $this->Form->select('Sendmail.employers', $employerUserList, array('multiple' => true, 'escape' => false, "id" => "Newsletter", 'class' => 'full-width required', 'style' => "display:none;height:200px!important")); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group" id="jobseeker_user">
                            <label class="col-sm-2 col-sm-2 control-label">Select Email <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <table cellpadding="5">
                                    <tr>
                                        <td><input id="all" type="radio" name="data[Sendmail][jobseekstatus]" value="1" onchange="show_newsletter1(this.value);" checked></td>
                                        <td>Send mail to all Jobseeker</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <input id="sendTo" type="radio" name="data[Sendmail][jobseekstatus]" value="2" onchange="show_newsletter1(this.value);">
                                        </td>
                                        <td>Select Jobseeker to send a newsletter</td>
                                    </tr>
                                    <tr>
                                        <td valign="top" colspan="2"> 
                                            <?php echo $this->Form->select('Sendmail.jobseekers', $jobseekerUserList, array('multiple' => true, 'escape' => false, "id" => "Newsletter1", 'class' => 'full-width required', 'style' => "display:none;height:200px!important")); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <?php
        echo $this->Form->hidden('Newsletter.id');
        ?>
        <div class="col-lg-10">
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'newsletters', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
        </div>


    </section>
</section>
<?php echo $this->Form->end(); ?>

