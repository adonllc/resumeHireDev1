function countUp(count, totalId, startClass)
{
    var count = document.getElementById(totalId).innerHTML;
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.'+startClass),
        run_count = 1,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}
countUp(495, 'showTotalTravelers', 'startTotalTravelers');
countUp(947, 'showTotalDrivers', 'startTotalDrivers');
countUp(256, 'showTotalAmenities', 'startTotalAmenities');