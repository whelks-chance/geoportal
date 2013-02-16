GeoPortal.Charts.QualCloud = Ext.extend(Ext.Container, {
    layout: 'fit',
    width: 600,
    height: 770,
    padding: '5px',
    ID: null,
    html: '<div id="tagCloud"><h2>Tag Cloud</h2></div>',
    initComponent: function () {
        GeoPortal.Charts.QualCloud.superclass.initComponent.call(this);
        this.on('afterrender', this.renderCloud, this);
    },
    renderCloud: function () {

        Ext.Ajax.request({
            params: { ID: this.ID },
            url: getTagsURL,
            success: function (data) {
                //var json = data;
                var data = Ext.util.JSON.decode(data.responseText);
                //create list for tag links
                jQuery("<ul>").attr("id", "tagList").appendTo("#tagCloud");

                //create tags

                for (var i = 0; i < data.tags.length; i++) {

                    var word = data.tags[i].word;
                    var count = data.tags[i].count;

                    //create item
                    var li = jQuery("<li>");

                    //create link
                    $("<a>").text(word).appendTo(li);

                    //set tag size
                    li.children().css("fontSize", (count / 10 < 0.5) ? count / 10 + 1 + "em" : (count / 10 > 1) ? "2em" : count /2  + "em");
                    li.children().css("color", (count/10 <=0.5) ? "#9966FF" : (count/10 >0.5 && count/10 <=1.0) ? "#990099" : (count/10 >1.0 && count/10 <=1.5) ? "#660099" : "#993399");

                    //add to list
                    li.appendTo("#tagList");
                }




            }

        });


      

    }
});