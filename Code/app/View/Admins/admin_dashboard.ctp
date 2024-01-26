<?php
$adminRols = ClassRegistry::init('Admin')->getAdminRoles($this->Session->read('adminid'));

$adminLId = $this->Session->read('adminid');
?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <!--state overview start-->
        <div class="row state-overview">
            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 1)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_customers; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_customers; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Employers', array('controller' => 'users', 'action' => 'index')); ?>
                        </p>
                    </div>

                </section>
            </div>
            <?php } ?>
            
            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 2)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_candidate; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_candidate; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Jobseekers', array('controller' => 'candidates', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } ?>

            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 4)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-sitemap"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_categories; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_categories; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Categories', array('controller' => 'categories', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } ?>

            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 6)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-laptop"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_skill; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_skill; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Skills', array('controller' => 'skills', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } ?>

            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 7)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_designation; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_designation; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Designation', array('controller' => 'designations', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } ?>
                       
            <?php /* if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 11)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_location; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_location; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Locations', array('controller' => 'locations', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } */ ?>

            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 3)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-suitcase"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_job; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_job; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Jobs', array('controller' => 'jobs', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } ?>

            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 5)){ ?>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    </div>
                    <div class="value">
                        <div id="showTotalTravelers" style="display: none;"><?php echo $total_blog; ?></div>
                        <h1 class="startTotalTravelers">
                            <?php echo $total_blog; ?>
                        </h1>
                        <p>
                            <?php echo $this->Html->link('Blogs', array('controller' => 'blogs', 'action' => 'index')); ?>
                        </p>
                    </div>
                </section>
            </div>
            <?php } ?>

        </div>
        <div class="row">
            <!--pie chart start-->
            <!-- <div class="col-lg-4">
                
                <section class="panel">
                    <div class="panel-body">
                        <div class="chart">
                            <div id="user-pie-chart1"></div>
                        </div>
                    </div>
                    <footer class="pie-foot">
                        <span style="color: #41CAC0">Total Employers: <?php //echo $total_customers;   ?></span>
                    </footer>
                </section>
                
            </div>-->
            <!--pie chart ends-->
            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 2)){ ?>
            <div class="col-lg-6">
                <!--new earning start-->
                <div class="panel terques-chart">
                    <div class="panel-body chart-texture">
                        <div class="chart">
                            <div class="heading">
                                <span> Showing Jobseekers from this month <strong>(<?php echo date('F') . ' 1' ?> </strong>  to <strong> <?php echo date('F') . ' ' . date('d') ?>)</strong> </span>
                            </div>
                            <div class="project-sparkline-job"></div>
                        </div>
                    </div>
                    <div class="chart-tittle">
                        <span class="title">Total <?php echo $total_jobseeker_no; ?> Jobseekers in last <?php echo $total_jobseeker_time; ?> days </span>
                        <span class="value">
                            <!-- <a href="javascript:void(0);">
                                 Max <?php //echo $max_user;   ?> Employers 
                             </a>-->
                        </span>
                    </div>
                </div>
                <!--new earning end-->
            </div>
            <?php } ?>

            <?php if(ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 1)){ ?>
            <div class="col-lg-6">
                <!--new earning start-->
                <div class="panel terques-chart">
                    <div class="panel-body chart-texture">
                        <div class="chart">
                            <div class="heading">
                                <span> Showing Employers from this month <strong>(<?php echo date('F') . ' 1' ?> </strong>  to <strong> <?php echo date('F') . ' ' . date('d') ?>)</strong> </span>
                            </div>
                            <div class="project-sparkline"></div>
                        </div>
                    </div>
                    <div class="chart-tittle">
                        <span class="title">Total <?php echo $total_user_no; ?> Employers in last <?php echo $total_user_time; ?> days </span>
                        <span class="value">
                            <!-- <a href="javascript:void(0);">
                                 Max <?php //echo $max_user;   ?> Employers 
                             </a>-->
                        </span>
                    </div>
                </div>
                <!--new earning end-->
            </div>
            <?php } ?>

        </div>

    </section>
</section>

<script type="text/javascript">
    $(document).ready(function () {


        $(".project-sparkline").sparkline([[<?php echo implode('],[', $user_datas); ?>]], {
            type: 'line',
            width: '90%',
            height: '235',
            fillColor: false,
            spotRadius: 4,
            spotColor: '#FFF',
            lineColor: '#FFF',
            lineWidth: 4,
            highlightLineColor: '#FFF',
            normalRangeColor: '#FFF',
            tooltipFormat: '<span style="color: #85FFFF">{{x}} <?php echo date('M') ?> : </span><span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}'
                    // tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
                    //   '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
        });



        $("#user-pie-chart1").sparkline([<?php echo $total_customers; ?>], {
            type: 'pie',
            //width: '300',
            height: '250',
            borderColor: '#00bf00',
            sliceColors: ['#41CAC0', '#F8D347']
                    //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
                    //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
        });



        $(".project-sparkline-job").sparkline([[<?php echo implode('],[', $jobseeker_datas); ?>]], {
            type: 'line',
            width: '90%',
            height: '235',
            fillColor: false,
            spotRadius: 4,
            spotColor: '#FFF',
            lineColor: '#FFF',
            lineWidth: 4,
            highlightLineColor: '#FFF',
            normalRangeColor: '#FFF',
            tooltipFormat: '<span style="color: #85FFFF">{{x}} <?php echo date('M') ?> : </span><span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}'
                    // tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
                    //   '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
        });


    });
</script>