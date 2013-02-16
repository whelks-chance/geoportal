
var server = "http://unlock.edina.ac.uk/ws/"
$(document).ready(function () {
    $("#autoNameField").autocomplete(server + "nameSearch", {
        dataType: 'jsonp',
        parse: function (data) {
            var rows = new Array();
            data = data.features;
            for (var i = 0; i < data.length; i++) {
                rows[i] = { data: data[i], value: data[i].properties.name, result: data[i].properties.name };
            }
            return rows;
        },

        formatItem: function (row, i, n) {
            return row.properties.name + ", " + row.properties.country + "<br/><span style='font-size:70%'>" + row.properties.featuretype + "<span>";
        },

        extraParams: {
            // Unlock doesn't support 'q' and 'limit', which are the autocomplete plugin defaults, so let's blank them out.
            q: '',
            limit: '',
            format: 'json', // Retrieve the results in JSON format
            maxRows: 10, // Limit the number of results to 10
            count: 'no', // Prevent Unlock from counting the total possible results (faster)
            name: function () { return $("#autoNameField").val() }
        },
        max: 10
    });
});  