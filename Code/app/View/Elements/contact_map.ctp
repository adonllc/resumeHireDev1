<div id="map" class="maps"></div>

<?php
// pr($contact_details);exit;
$j = 1;
$k = count($contact_details);
if ($contact_details) {
    $locationTop = '';

    $pin = 'locetion.png';
    $name = $contact_details['Setting']['company_name'];
    $addressmap = $contact_details['Setting']['address'];
    $locationTop .= "['" . $pin . "'," .
            "'" . addslashes($name) . "'," .
            "'" . $contact_details['Setting']['latitude'] . "'," .
            "'" . $contact_details['Setting']['longitude'] . "'," .
            "'" . $addressmap . "'," .
            "], ";

    $j++;
} else {
    $locationTop = '';
}

// echo $locationTop;exit;
?>




<script type="text/javascript">
    var currentInfoWindow = null;


    var locations = [
<?php echo $locationTop; ?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: new google.maps.LatLng(locations[0][2], locations[0][3]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });


    var marker, i;

    for (i = 0; i < locations.length; i++) {
        var image = '<?php echo HTTP_PATH; ?>/app/webroot/img/front/'+locations[i][0];
      
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][2], locations[i][3]),
            map: map,
            //shadow:'<?php echo HTTP_PATH; ?>/app/webroot/img/front/locetion.png',
            icon: new google.maps.MarkerImage(image,
            new google.maps.Size(56,56),
            new google.maps.Point(0,0),
            new google.maps.Point(25,63)),
            //    icon: image,
            title:locations[i][1]

        });

        google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
            if(i>= 0){
                var content = document.createElement("DIV");
                content.className = "gmapInfoWindow";
        
                content.innerHTML = '<div class="dname"><div class="dname_img_2"><div class="dname_1"> Company Name: '+locations[i][1] +'</div><div class="dname_2">Address: '+locations[i][4]+'</div></div></div>'  ;
        
                var myOptions = {
                    content: content
                    ,closeBoxMargin: "-180px 14px 0 0"
                    ,closeBoxURL: "https://www.google.com/intl/en_us/mapfiles/close.gif"
                    ,infoBoxClearance: new google.maps.Size(1, 1)
                    ,isHidden: false
                    ,enableEventPropagation: false
                    ,disableAutoPan: true
                    ,pixelOffset: new google.maps.Size(0, 0)
                };
        
                var infowindow = new google.maps.InfoWindow({
                    content: content
                });
        
                return function() {
                    if (currentInfoWindow != null) {
                        currentInfoWindow.close();
                    }
                    //infowindow = new InfoBox(myOptions);
                    infowindow.open(map, marker);
                    currentInfoWindow = infowindow;
                }
            }
        })(marker, i));



     

    }
</script>


