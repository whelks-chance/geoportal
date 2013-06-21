GeoPortal.Forms.QuestionMatching = Ext.extend(Ext.form.FormPanel, {

    id: 'frmQuestionMatch',
    title: 'Question Matching',
    layout: 'fit',

    initComponent: function () {

        var datasetID = "";

        var wiserdStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            id: "wiserdMatchStore",
            root : "questionData"
        });

        var remoteStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            id: "remoteMatchStore"
//            root : ""
        });


        this.items = [
            {
                xtype: 'container',
                layout: 'column',
//                padding : '20',
                items: [
                    {

                        //  WISERD q's

                        xtype: 'container',
                        title: 'WISERD questions',
                        columnWidth: 0.5,
                        layout: 'form',
//                        padding: 20,
                        style: 'margin:10px;',
                        items: [
                            {
                                // Text field for the keyword to be typed in

                                xtype: 'textfield',
                                id: 'txtLocalQuestionMatchKeyword',
                                emptyText: 'Keywords...',
                                anchor: '100%',
                                fieldLabel: 'Keyword',
                                name: 'Keyword'
                            },
                            {
                                // Search button.
                                // Takes the keyword and the value from the source dropdown and sends to RemoteData/doKeywordSearch
                                // After results arrive, loads the dataset store

                                xtype: 'button',
                                id: 'btnLocalQuestionMatch',
                                icon: 'images/silk/magnifier.png',
                                text: 'Search',
                                tooltip: 'Submit Search',
                                handler: function() {
                                    var txtcmp = Ext.getCmp('txtLocalQuestionMatchKeyword');
                                    var keyword = txtcmp.getValue();

                                    Ext.Ajax.request({
                                        url: getWiserdQuestions,
                                        params : {Keyword : keyword},
                                        method : 'POST',

                                        success: function(resp) {
                                            console.log('success!');
                                            var responseData = Ext.decode(resp.responseText);
                                            console.log(responseData);

                                            wiserdStore.loadData(responseData);
                                        },
                                        failure: function(resp) {
                                            console.log('failure!');
                                        }
                                    });
                                }
                            },
                            {
                                // Drop down for the dataset
                                // Lets user select a survey, sends the id to RemoteData/getRemoteDataset
                                // Returns the regions available for the data

                                xtype: 'combo',
                                tpl: '<tpl for="."><div ext:qtip="{name}" class="x-combo-list-item">{name}</div></tpl>',
                                id: 'cmboWISERDq',
                                anchor: '100%',
                                fieldLabel: 'Select WISERD question',
                                name: 'Dataset',
                                triggerAction: 'all',
                                displayField: 'name',
                                hiddenName: 'hiddenWISERDid',
                                valueField: 'id',
                                listeners: {
                                    'select': function(t){
                                        var cmboDataset = Ext.getCmp('cmboWISERDq');
                                        datasetID = cmboDataset.getValue();

                                        var txtcmp = Ext.getCmp('matchingLocaljsonarea');

                                        var localText = txtcmp.getValue();

                                        txtcmp.setValue(localText + '\r\n' + datasetID);
                                    }
                                },
                                mode: 'local',
                                store : wiserdStore
                            },
                            {
                                fieldLabel      : 'csvarea',
                                id              : 'matchingLocaljsonarea',
                                name            : 'csv',
                                xtype           : 'textarea',
                                autoScroll      : true,
                                height          : 260,
                                anchor: '100%'

                            }
                        ]
                    },
                    {

                        //  REMOTE q's

                        xtype: 'container',
                        title: 'Remote questions',
                        columnWidth: 0.5,
                        layout: 'form',
//                        padding: 20,
                        style: 'margin:10px;',
                        items: [
                            {

                                xtype: 'textfield',
                                id: 'txtRemoteQuestionMatchKeyword',
                                emptyText: 'Keywords...',
                                anchor: '100%',
                                fieldLabel: 'Keyword',
                                name: 'Keyword'
                            },
                            {
                                // Search button.
                                // Takes the keyword and the value from the source dropdown and sends to RemoteData/doKeywordSearch
                                // After results arrive, loads the dataset store

                                xtype: 'button',
                                id: 'btnRemoteQuestionMatch',
                                icon: 'images/silk/magnifier.png',
                                text: 'Search',
                                tooltip: 'Submit Search',
                                handler: function() {
                                    var txtcmp = Ext.getCmp('txtRemoteQuestionMatchKeyword');
                                    var keyword = txtcmp.getValue();

                                    Ext.Ajax.request({
                                        url: remoteDataKeywordSearchURL,
                                        params : {Keyword : keyword},
                                        method : 'POST',

                                        success: function(resp) {
                                            console.log('success!');
                                            var responseData = Ext.decode(resp.responseText);
                                            console.log(responseData);

                                            remoteStore.loadData(responseData);
                                        },
                                        failure: function(resp) {
                                            console.log('failure!');
                                        }
                                    });
                                }
                            },
                            {
                                // Drop down for the dataset
                                // Lets user select a survey, sends the id to RemoteData/getRemoteDataset
                                // Returns the regions available for the data

                                xtype: 'combo',
                                tpl: '<tpl for="."><div ext:qtip="{name}" class="x-combo-list-item">{name}</div></tpl>',
                                id: 'cmboRemoteq',
                                anchor: '100%',
                                fieldLabel: 'Select Remote DataSet',
                                name: 'Dataset',
                                triggerAction: 'all',
                                displayField: 'name',
                                hiddenName: 'hiddenRemoteID',
                                valueField: 'id',
                                listeners: {
                                    'select': function(t){
                                        var cmboDataset = Ext.getCmp('cmboRemoteq');
                                        datasetID = cmboDataset.getValue();

                                        var txtcmp = Ext.getCmp('matchingRemotejsonarea');

                                        var localText = txtcmp.getValue();

                                        txtcmp.setValue(localText + '\r\n' + datasetID);

                                    }
                                },
                                mode: 'local',
                                store : remoteStore
                            },
                            {
                                fieldLabel      : 'csvarea',
                                id              : 'matchingRemotejsonarea',
                                name            : 'csv',
                                xtype           : 'textarea',
                                autoScroll      : true,
                                height          : 260,
                                anchor: '100%'

                            }
                        ]
                    },
                    {

                        xtype: 'button',
                        id: 'btnSaveQuestionMatch',
                        icon: 'images/silk/magnifier.png',
                        text: 'Save',
                        tooltip: 'Submit Search',
                        handler: function() {
                            var localtxtcmp = Ext.getCmp('matchingLocaljsonarea');
                            var localkeyword = localtxtcmp.getValue();

                            var remotetxtcmp = Ext.getCmp('matchingRemotejsonarea');
                            var remotekeyword = remotetxtcmp.getValue();


                            Ext.Ajax.request({
                                url: linkRemoteQuestion,
                                params : {wiserdID : localkeyword, remoteID : remotekeyword},
                                method : 'POST',

                                success: function(resp) {
                                    console.log('success!');
                                    var responseData = Ext.decode(resp.responseText);
                                    console.log(responseData);
                                    alert("saved");
//                                    wiserdStore.loadData(responseData);
                                },
                                failure: function(resp) {
                                    console.log('failure!');
                                    alert("save failed");
                                }
                            });
                        }
                    }
                ]
            }
        ];
        GeoPortal.Forms.QuestionMatching.superclass.initComponent.call(this);
    }

});