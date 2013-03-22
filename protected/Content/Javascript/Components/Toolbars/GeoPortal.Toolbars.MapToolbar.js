
GeoPortal.Toolbars.MapToolbar = Ext.extend(Ext.Toolbar, {
    layout: 'anchor',
    id: 'mapToolbar',
    initComponent: function () {
        this.items = this.CreateItems();
        GeoPortal.Toolbars.MapToolbar.superclass.initComponent.call(this);
    },

    OnDrawClick: function (e) {
        disableTools();
        Ext.getCmp('mappanel').body.applyStyles('cursor:pointer');
        click.activate();
        annotateMap = true;
    },
    OnFindClick: function () {
        var FindTB = Ext.getCmp('findTB');
        if (FindTB.isVisible()) {

            disableTools();

            FindTB.hide();
        } else {
            FindTB.show();
        }


    },
    OnMeasureClick: function () {
        disableTools();
    },
    OnFSClick: function () {
        //get TOC by ID using Ext find method
        var TOC = Ext.getCmp('TOC');
        //check to see if visible
        if (TOC.isVisible()) {
            TOC.hide();
        } else {
            TOC.show();
        }
    },
    OnPrintClick: function () { },

    OnGuideClick: function (b, e) {
        window.open("Content/PDF/UserGuide.pdf", "WISERD Geoportal User Guide", "resizable,scrollbars");
        return false;
    },

    OnSaveClick: function () { },
    OnMapSettingsClick: function () { },
    OnBugClick: function () {
        var bugWin = new GeoPortal.Windows.BugReport();
        bugWin.show();
        var today = new Date();
        Ext.getCmp('sendDate').setValue(today);

    },
    OnLoginClick: function () {

        var login = new GeoPortal.Forms.Login();
        var lwin = new Ext.Window({
            title: 'Login',
            id: 'LoginWin',
            resizable: false,
            items: [login],
            modal: true
        });

        lwin.show();

    },
    OnMAClick: function () {

        var myAC = new GeoPortal.Windows.MyAccount();
        Ext.getCmp("frmMyDetails").getForm().load({
            url: accountURL,
            waitMsg: 'Loading.......',
            method: 'POST',
            sucess: myAC.show(),
            failure: Ext.Msg.alert('Account Error', 'You are no longer logged in. Please Refresh the page and try again')
    });

    },
    CreateItems: function () {

        var ctrl, toolbarItems = [], action, actions = {};

        // ZoomToMaxExtent control
        action = new GeoExt.Action({
            control: new OpenLayers.Control.ZoomToMaxExtent(),
            map: map,
            icon: 'images/silk/world.png',
            tooltip: "Zoom to max extent"
        });
        actions["max_extent"] = action;
        toolbarItems.push(action);

        // Navigation controls
        // in the same toggle group
        action = new GeoExt.Action({
            icon: 'images/geosilk/pan.png',
            control: new OpenLayers.Control.Navigation(),
            map: map,
            // button options
            toggleGroup: "draw",
            allowDepress: false,
            pressed: true,
            tooltip: "navigate",
            // check item options
            group: "draw",
            checked: true
        });
        actions["nav"] = action;
        toolbarItems.push(action);

        // Navigation history - two "button" controls
        ctrl = new OpenLayers.Control.NavigationHistory();
        map.addControl(ctrl);

        action = new GeoExt.Action({
            icon: 'images/silk/arrow_left.png',
            control: ctrl.previous,
            disabled: true,
            tooltip: "previous view"
        });
        actions["previous"] = action;
        toolbarItems.push(action);

        action = new GeoExt.Action({
            icon: 'images/silk/arrow_right.png',
            control: ctrl.next,
            disabled: true,
            tooltip: "next view"
        });
        actions["next"] = action;
        toolbarItems.push(action);
        toolbarItems.push("-");


        toolbarItems.push('-');

        //draw button
        btnDraw = new Ext.Button({
            icon: 'images/silk/comment_edit.png',
            tooltip: "Annotate this Map",
            handler: this.OnDrawClick,
            toggleGroup: "tools",
            group: "tools"
        });

        toolbarItems.push(btnDraw);

        var selectCtrl = new OpenLayers.Control.SelectFeature(resultsLayers, {
            clickout: true,
            //initLayer: map.layers,
            enableToggle: true,
            multiple: false,
            hover: false,
            multipleKey: "shiftKey",
            id: 'selectCtrl',
            handler: disableTools()
        });

        map.addControl(selectCtrl);


     
        var identify = new GeoExt.Action({
            tooltip: "Identify Features",
            icon: 'images/silk/information.png',
            enableToggle: true,
            pressed: false,
            control: InfoClick,
            toggleGroup: 'tools',
            allowDepress: true,
            map: map,
            handler: function (toggled) {

                if (toggled.pressed != false) {
                    InfoClick.activate();
                    Ext.getCmp("mappanel").body.applyStyles("cursor:help");
                } else {
                    InfoClick.deactivate();
                    Ext.getCmp("mappanel").body.applyStyles("cursor:default");

                }
            }
        });

        //actions["select"] = identify;
        toolbarItems.push(identify);


        locData = new Ext.Button({
            icon: 'images/silk/find.png',
            tooltip: "Search..",
            handler: this.OnFindClick,
            toggleGroup: "tools",
            group: "tools",
            id: 'btnFind'
        });
        toolbarItems.push(locData);

        //clear map graphics

        var clearSelect = new Ext.Button({
            tooltip: "Clear Map Graphics",
            icon: 'images/geosilk/erase.png',
            handler: function () {
                var element = document.getElementById('txtStatusMessage');
                element.innerHTML = "";
                anno.removeFeatures(anno.features);
                layerRuler.removeFeatures(layerRuler.features);
                length.cancel();
                area.cancel();
                SearchLayer.destroyFeatures();
            }
        });

        toolbarItems.push(clearSelect)
        //measure length

        var measureLength = new GeoExt.Action({
            tooltip: "Measure Length",
            icon: 'images/geosilk/ruler.png',
            toggleGroup: "tools",
            pressed: false,
            allowDepress: false,
            control: length,
            map: map,
            handler: function () {
                Ext.getCmp('mappanel').body.applyStyles('cursor:crosshair');
                var element = document.getElementById('txtStatusMessage');
                element.innerHTML = "";
            }

        });
        toolbarItems.push(measureLength);

        //measure area

        var measureArea = new GeoExt.Action({
            tooltip: "Measure Area",
            icon: 'images/geosilk/ruler_square.png',
            toggleGroup: "tools",
            pressed: false,
            allowDepress: false,
            control: area,
            map: map,
            handler: function () {
                Ext.getCmp('mappanel').body.applyStyles('cursor:crosshair');
                var element = document.getElementById('txtStatusMessage');
                element.innerHTML = "";
                layerRuler.removeFeatures(layerRuler.features);
            }
        });

        toolbarItems.push(measureArea);
        toolbarItems.push("-");



        //Help-User Guide Button
        btnUserGuide = new Ext.Button({
            icon: 'images/silk/help.png',
            tooltip: "Help & User Guide (Opens in a new window)",
            handler: this.OnGuideClick
        });
        toolbarItems.push(btnUserGuide);
        toolbarItems.push("->");



        //Search,Print,Save etc
        simpleSearch = {
            xtype: 'form',
            id: 'frmSimpleSearch',
            width: 400,
            border: false,
            frame: true,
            reset: true,
            monitorValid: true,
            labelWidth: 60,
            unstyled: true,
            bodyStyle: 'padding-top: 3px',
            items: [{
                xtype: 'textfield',
                id: 'txtSearch',
                anchor: '95%',
                name: 'Keywords',
                fieldLabel: '<b>Keywords</b>',
                allowBlank: false,
                emptyText: 'Keyword search.....'
            }],
            keys: [{
                key: [Ext.EventObject.ENTER], handler: function () {
                    doSearch();
                }

            }]

        };
        var searchBtn = {
            xtype: 'button',
            icon: 'images/silk/magnifier.png',
            id: 'btnSimpleSearch',
            tooltip: 'Search....',
            iconAlign: 'right',            
            handler: function () {
                doSearch();
            }
        };

        var advSearchBtn = { xtype: 'button',
            icon: 'images/silk/magnifier_zoom_in.png',
            id: 'btnAdvSearch',
            tooltip: 'Advanced Search... (Under development)',
            disabled: false,
            handler: function () {
                //get TOC by ID using Ext find method
                var advSearch = Ext.getCmp('advSearch');
                //check to see if visible

                if (advSearch != null) {
                    advSearch.show();
                } else {
                    advSearch = new Ext.Window({
                        title: 'Advanced Search',
                        id: 'advSearch',
                        resizable: false,
                        animateTarget: Ext.getCmp("btnAdvSearch").el,
                        items: [new GeoPortal.Forms.AdvancedSearch()]
                    });
                    advSearch.show();
                }

            }
        };

        toolbarItems.push(simpleSearch);
        toolbarItems.push(searchBtn);
        toolbarItems.push(advSearchBtn);
        toolbarItems.push("-");

        btnFS = new Ext.Button({
            icon: 'images/silk/layout.png',
            id: 'btnTOC',
            tooltip: "Show TOC..",
            toggleGroup: "FS",
            group: "FS",
            pressed: true,
            handler: this.OnFSClick
        });
        toolbarItems.push(btnFS);


        btnPrint = new Ext.Button({
            id: 'btnTBPrint',
            disabled: true,
            icon: 'images/silk/printer.png',
            tooltip: "Print Current View...(Under development)",
            handler: this.OnPrintClick
        });
        toolbarItems.push(btnPrint);

        btnSave = new Ext.Button({
            id: 'btnTBSave',
            icon: 'images/silk/disk.png',
            tooltip: "Save View...(Under development)",
            disabled: true,
            handler: this.OnSaveClick
        });
        toolbarItems.push(btnSave);
        //Map Options
        toolbarItems.push("-");
        btnMapOptions = new Ext.Button({
            icon: 'images/silk/map_edit.png',
            tooltip: "Map Settings...(Under development)",
            handler: this.OnMapSettingsClick,
            disabled: true

        });

        toolbarItems.push(btnMapOptions);
        //feedback
        toolbarItems.push("-");
        btnBug = new Ext.Button({
            icon: 'images/silk/bug.png',
            tooltip: "Report an issue...(Under development)",
            handler: this.OnBugClick
        });
        toolbarItems.push(btnBug);

        //login
        toolbarItems.push("-");
        btnLogin = new Ext.Button({
            id: 'btnTBLogin',
            icon: 'images/silk/key.png',
            tooltip: "Login..(Under development)",
            disabled: true,
            handler: this.OnLoginClick
        });
        toolbarItems.push(btnLogin);

        btnMA = new Ext.Button({
            id: 'btnTBMyAcc',
//            disabled: true,
            icon: 'images/silk/user_green.png',
            tooltip: "My Account..(Under development)",
            handler: this.OnMAClick
        });
        toolbarItems.push(btnMA);
        //spacer
        toolbarItems.push({ xtype: 'tbspacer', width: 20 });
        return toolbarItems;
    }
});
        
//Ext.reg('MapToolbar', GeoPortal.Toolbars.MapToolbar);


function doSearch() {
           var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving Search Results...."});
          
          var resWin = Ext.getCmp('resWin');
                if (resWin != null) {
                    resWin.destroy();
                };
                loadMask.show();
                var frmSimpleSearch = Ext.getCmp('frmSimpleSearch');

                if (frmSimpleSearch.form.isValid()) {
                    frmSimpleSearch.getForm().submit({
                        url: VerifySearchURL,
                        timeout: 120,
                        params: { start: 0, limit: 15, mappable: false },
                        //method: 'post',
                        //waitMsg: 'Retrieving Search Results....',
                        success: function () {
                            var txtfld = Ext.getCmp('txtSearch');
                            var val;
                            if (val == null) { val = txtfld.getValue() };
                            var resWin = new GeoPortal.Windows.Results({ title: 'Results - Search terms "' + val + '"' });
                            resWin.qualStore.removeAll();
                            resWin.resStore.removeAll();

                            resWin.qualStore.load({params: {keywords: val, start: 0, limit: 15}});
                            resWin.resStore.load({ url: simpleSearchURL, params: { keywords: val, start: 0, limit: 15, mappable: false }, callback: function () {
                                 resWin.show(); 
                                 loadMask.hide();
                            } 
                            });
                             //txtfld.setValue('');
                             //var form = Ext.getCmp('frmSimpleSearch').getForm();//.reset();
                          
                        },
                        failure: function () {
                             loadMask.hide();
                            Ext.Msg.alert('No Results Found', 'Sorry no results were found matching your search terms');

                        }
                    })
               }
         }
            
            
function disableTools(){
            
             var FindTB = Ext.getCmp('findTB');
             if (FindTB){
                for (var i = 0; i < FindTB.topToolbar.items.length; i++) {
                    var button =FindTB.topToolbar.items.items[i];
                    button.toggle(false);
                }
            }
            
            }   