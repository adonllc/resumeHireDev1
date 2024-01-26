<div style="width:100%;">
    <?php 
    if ($this->Session->check('error_msg')) {
        echo "<div class='error_msg error_lo'><span class='span_text'>" . $this->Session->read('error_msg') . "</span></div>";
       // $this->Session->delete("error_msg");
        $this->Session->consume("error_msg");
        }
        if ($this->Session->check('success_msg')) {
        echo "<div class='success_msg success_lo'><span class='span_text'>" . $this->Session->read('success_msg') . "</span></div>";
        //$this->Session->delete("success_msg");
        $this->Session->consume("success_msg");
    }
    ?>
</div>