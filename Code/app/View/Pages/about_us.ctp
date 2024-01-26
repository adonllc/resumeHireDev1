<section class="abouts_page_section">
    <div class="container">
        <h1><?php echo __d('home', 'About Us', true) ?></h1>
           <p>
        <?php 
          $static_page_description = 'static_page_description';
                if ($_SESSION['Config']['language'] != 'en') {
                    $static_page_description = 'static_page_description_' . $_SESSION['Config']['language'];
                }
                echo classregistry::init('Page')->field($static_page_description, array('Page.static_page_heading' => 'about-us')); ?></p>
        <div class="about-cin">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="about-clients-view">
                        <h2>250M</h2>
                        <span><?php echo __d('home', 'Unique monthly visitors', true) ?></span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="about-clients-view">
                        <h2>15M</h2>
                        <span><?php echo __d('home', 'Resumes', true) ?></span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="about-clients-view">
                        <h2>15M</h2>
                        <span><?php echo __d('home', 'Total ratings and reviews', true) ?></span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="about-clients-view">
                        <h2>10</h2>
                        <span><?php echo __d('home', 'Jobs added per second globally', true) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="abouts_best_section">
    <div class="container">
        <h1><?php echo __d('home', 'Why We are the best', true) ?></h1>
        <div class="about-best-cin">
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="about-best-view">
                        <i><?php echo $this->Html->image('front/home/icon-1.png', array('alt' => 'icon')); ?></i>
                        <h2><?php echo __d('home', 'Uniquely qualified', true) ?></h2>
                        <span><?php echo __d('home', 'Get your job in front of Recruter members who are active on our network, engaged in their careers, and open to new opportunities.', true) ?> </span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="about-best-view">
                       <i><?php echo $this->Html->image('front/home/icon-2.png', array('alt' => 'icon')); ?></i>
                        <h2><?php echo __d('home', 'Targeted matches', true) ?></h2>
                        <span><?php echo __d('home', 'Our network gives us a deep, up-to-date, and insightful dataset of professionals. We use that data to match your role to the most qualified professionals.', true) ?> </span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="about-best-view">
                        <i><?php echo $this->Html->image('front/home/icon-3.png', array('alt' => 'icon')); ?></i>
                        <h2><?php echo __d('home', 'Only pay for results', true) ?> </h2>
                        <span><?php echo __d('home', "Pay only when candidates view your job post. Tell us your budget, and we can estimate how many applicants you'll receive.", true) ?> </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .abouts_page_section ol li ol {
    padding-left: 18px;
}
.abouts_page_section ol li ol li {
    list-style-type: lower-alpha;
}

.abouts_page_section ol li {
    display: list-item;
    text-align: -webkit-match-parent;
    list-style-type: decimal;
}
   .abouts_page_section ul li {
    display: list-item;
    text-align: -webkit-match-parent;
    list-style-type: disc;
}
    </style>