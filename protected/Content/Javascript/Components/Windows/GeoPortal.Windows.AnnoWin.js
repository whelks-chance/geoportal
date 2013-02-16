var labelBGColor = 'white';
var labelTextColor = 'black';
var colorMenuBG;
var colorMenuTxt;

function createColorMenus() {
    colorMenuBG = new Ext.menu.ColorMenu({
        handler: function (cm, color) {
            labelBGColor = color;
            document.getElementById('color').style.backgroundColor = '#' + color;
            Ext.getCmp('currentTextColor').html = '</div width="20px" height="10px" style="background-color:#' + color + ';" >';
        }
    });

    colorMenuTxt = new Ext.menu.ColorMenu({
        handler: function (cm, color) {
            labelTextColor = color;
            document.getElementById('text').style.color = '#' + color;
            Ext.getCmp('currentTextColor').html = '</div width="20px" height="10px" style="background-color:#' + color + ';" >';
        }
    });
}

GeoPortal.Windows.AnnoWin = Ext.extend(Ext.Window, {
    title: 'Add Annotation',
    id: 'annoWin',
    bodyStyle: 'padding: 5px;',
    defaultButton: 'Save',
    evt: null,
    modal: true,
    resizeable: false,
    maximisable: false,
    autoHeight: true,
    initComponent: function () {
        this.items = [
            {
                xtype: 'form',
                bodyStyle: 'padding: 5px',
                monitorValid: true,
                id: 'frmAnno',
                buttons: [
                    {
                        xtype: 'button',
                        text: 'Add',
                        icon: '../images/silk/tag_blue.png',
                        handler: function () {
                            var annoWin = Ext.getCmp('annoWin');
                            var px = annoWin.evt.xy;
                            var text = Ext.getCmp('txt2Anno').getValue();
                            var pntSize = Ext.getCmp('sldrPointSize').getValue();
                            var fontSize = Ext.getCmp('sldrFontSize').getValue();
                            var offsetx = (2 * pntSize);
                            var offsety = (2 * pntSize);
                            addLabel(px, offsetx, offsety, text, labelBGColor, labelTextColor, pntSize, fontSize);
                            annoWin.destroy();
                            click.activate();
                            labelBGColor = 'white';
                            labelTextColor = 'black';
                            
                        }
                    }],
                items: [
                    {
                        xtype: 'textarea',
                        width: 300,
                        height: 100,
                        hideLabel: true,
                        emptyText: 'Annotation Text.....',
                        anchor: '95%',
                        id: 'txt2Anno'
                    },
                    {
                        xtype: 'sliderfield',
                        value: 3,
                        fieldLabel: 'Point Size',
                        anchor: '90%',
                        width: 100,
                        maxValue: 15,
                        minValue: 1,
                        id: 'sldrPointSize'
                    },
                    {
                        xtype: 'sliderfield',
                        value: 8,
                        fieldLabel: 'Font Size',
                        anchor: '90%',
                        width: 100,
                        maxValue: 15,
                        minValue: 1,
                        id: 'sldrFontSize'
                    },
                    {
                        xtype: 'label',
                        text: 'Example Annotation:'
                    },
                    {
                        xtype: 'panel',
                        id: 'currentTextColor',
                        width: 50,
                        html: '<div id="color" style="padding:3px"><a id="text" style="font-weight: bold; font-size: 12px;">Label</a></div>'
                        //bodyStyle: 'background-color: black',
                    },
                    {
                        xtype: 'button',
                        id: 'btnBGColor',
                        text: 'Background Colour',
                        icon: '../images/silk/color_wheel.png',
                        tooltip: 'Pick a colour....',
                        menu: colorMenuBG
                    },
                     {
                         xtype: 'button',
                         id: 'btnTxtColor',
                         text: 'Text Colour',
                         icon: '../images/silk/color_wheel.png',
                         tooltip: 'Pick a colour....',
                         menu: colorMenuTxt
                     }
                ]
            }
           ];
        GeoPortal.Windows.AnnoWin.superclass.initComponent.call(this);
    }
});