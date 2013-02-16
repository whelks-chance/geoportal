

Ext.onReady(function () {

    var map = CreateMap();
    var mapToolbar;
    
    
    GP = Ext.extend(GeoPortal.WGPViewPort, {
        initComponent: function () {
            GP.superclass.initComponent.call(this);
        }
    });

});