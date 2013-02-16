var geooptions = {
    timeout: 10000,
    maximumAge: 20000,
    enableHighAccuracy: false
};


function GetMyLocation() {

   
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getPos, errorPos, geooptions);
       } else {
        var centPoint = new OpenLayers.LonLat(-3.76622, 52.33);
        centPoint.transform(geographic, OSGB);
        map.setCenter(centPoint, 7);
        loadWin.hide();
    }
    // need to put catch in for users outside of the UK
    function getPos(pos) {
        var centPoint = new OpenLayers.LonLat(pos.coords.longitude, pos.coords.latitude);
        centPoint.transform(geographic, OSGB);
        map.setCenter(centPoint, 14);
        RemoveMask();
    }

    function errorPos(error) {

        if (err.code == 1) {
            //user said no
            RemoveMask();

        } else if (err.code == 3) {
        RemoveMask();
    } 
    RemoveMask();
     }

 }

//removes the semi-transparent 'mask' from the map area
function RemoveMask() {
    setTimeout(function () {
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({ remove: true });
    }, 250);
}

