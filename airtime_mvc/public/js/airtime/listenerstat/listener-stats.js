var api_data;

$(document).ready(function() {

    AIRTIME.listenerstats.setupDateRangePicker();
    AIRTIME.listenerstats.setupMostPopularShowsTable();
    AIRTIME.listenerstats.getAllReports();

    $("#back-to-world-map-btn").click(function() {
        $("#map_country").hide();
        $(this).hide();
        $("#map_world").show();
    });


    $("#stats-range-btn").click(mod.getAllReports);

});

var AIRTIME = (function(AIRTIME) {

    //Module initialization
    if (AIRTIME.listenerstats === undefined) {
        AIRTIME.listenerstats = {};
    }
    mod = AIRTIME.listenerstats;

    mod.getAllReports = function() {
        var start = $("#stats-start-date-picker").val();
        var end = $("#stats-end-date-picker").val();

        mod.getGeolocationStats(start, end);
        mod.getAggregateTuningMinutesStats(start, end);
        mod.refreshMostPopularShowsTable(start, end);
    };

    mod.refreshMostPopularShowsTable = function(startTimestamp, endTimestamp)
    {
        if (!AIRTIME.listenerstats.mostPopularShowsTable) {
            return;
        }
        var extraAjaxParams = {start: startTimestamp, end: endTimestamp};
        AIRTIME.listenerstats.mostPopularShowsTable.setExtraAjaxParameters(extraAjaxParams);
        AIRTIME.listenerstats.mostPopularShowsTable.refresh();
    }

    mod.setupMostPopularShowsTable = function()
    {
        var startTimestamp = $("#stats-start-date-picker").val();
        var endTimestamp = $("#stats-end-date-picker").val();

        var aoColumns = [
            /* Title */           { "sTitle" : $.i18n._("Name")              , "mDataProp" : "name"  ,   },
            /* Creator */         { "sTitle" : $.i18n._("Start Time")            , "mDataProp" : "starts"  , "sClass"      : "library_creator"     , "sWidth"      : "160px"                 },
            /* Upload Time */     { "sTitle" : $.i18n._("End Time")           , "mDataProp" : "ends"        , "bVisible"    : true                 , "sClass"      : "library_upload_time"   , "sWidth" : "155px"        },
            /* Website */         { "sTitle" : $.i18n._("Listeners")            , "mDataProp" : "listeners"     , "bVisible"    : true                 , "sClass"      : "library_url"           , "sWidth" : "150px"        },
        ];

        var extraAjaxParams = {start: startTimestamp, end: endTimestamp};
        var table = new AIRTIME.widgets.Table(
            $('#most-popular-shows-table'), //DOM node to create the table inside.
            false,                //Disable item selection
            [],    //Toolbar buttons
            {                    //Datatables overrides.
                'aoColumns' : aoColumns,
                'sAjaxSource' : 'http://localhost/rest/listener-stats/most-popular-shows'
            },
            {'html' : 'No popular shows found.'},
            extraAjaxParams
        );
        AIRTIME.listenerstats.mostPopularShowsTable = table;
        return table;
    }

    mod.getAggregateTuningMinutesStats = function(start, end) {
        $.get("/rest/listener-stats/aggregate-tuning", {start: start, end: end}, function(data) {
            data = JSON.parse(data);

            var data_sets = [];
            $.each(data, function(k, v) {
                var minutes = (v.session_duration)/60;
                var d = new Date(v.date);
                var list = [d, minutes];
                data_sets.push(list);
            });

            var tick_size = 60*60*24;
            var options = {
                series: {
                    lines: { show: true, fill: 0.3 },
                    points: { show: true }
                },
                yaxis: { min: 0, tickDecimals: 0 },
                xaxis: { mode: "time", timeformat: "%Y-%m-%d", tickSize: [tick_size, "second"]}
            };

            var flot_data = [];
            flot_data.push({data: data_sets});

            $.plot($("#aggregate-tuning"), flot_data, options);
        });
    }

    mod.setupDateRangePicker = function() {
        oBaseDatePickerSettings = {
            dateFormat: 'yy-mm-dd',
            //i18n_months, i18n_days_short are in common.js
            monthNames: i18n_months,
            dayNamesMin: i18n_days_short,
            onSelect: function(sDate, oDatePicker) {
                $(this).datepicker( "setDate", sDate );
            }
        };

        var d = new Date();
        d.setDate(d.getDate()-1);
        $("#stats-start-date-picker").val($.datepicker.formatDate("yy-m-dd", d));
        $("#stats-start-date-picker").datepicker(oBaseDatePickerSettings);

        d.setDate(d.getDate()+1);
        $("#stats-end-date-picker").val($.datepicker.formatDate("yy-m-dd", d));
        $("#stats-end-date-picker").datepicker(oBaseDatePickerSettings);

    }

    mod.getGeolocationStats = function(start, end) {
        $("#back-to-world-map-btn").click();

        $.get("/rest/listener-stats/global-geolocation", {start: start, end: end}, function(data) {
            api_data = JSON.parse(data);

            var country_map_data = [];
            country_map_data.push(["Country", "Listeners"]);

            if (Object.keys(api_data).length > 0) {
                $.each(api_data, function (k, v) {
                    var num_listeners = v.total
                    var list = [v.name, num_listeners.toString()];
                    country_map_data.push(list);
                });
            } else {
                // hack to make map draw with 0 listeners
                country_map_data.push(["unknown", 0]);
            }
            mod.drawWorldMap(country_map_data);
        });
    }

    mod.drawWorldMap = function(country_map_data) {
        var data = google.visualization.arrayToDataTable(country_map_data);

        var options = {};
        options['dataMode'] = 'regions';
        options['colors'] = [0xFF8747, 0xFFB581, 0xc06000]; //orange colors

        var container = document.getElementById('map_world');
        var geomap = new google.visualization.GeoMap(container);

        geomap.draw(data, options);

        google.visualization.events.addListener(geomap,
            'regionClick', function(e) {
                $("#map_world").hide();

                $("#map_country").show();
                drawCountryMap(e.region, api_data[e.region].cities);
                $("#back-to-world-map-btn").show();
            });
    }

    mod.drawCountryMap = function(country_code, cities) {
        var city_map_data = [];
        city_map_data.push(["City", "Listeners"]);
        $.each(cities, function(k,v) {
            var num_listeners = v;
            var list = [k, num_listeners.toString()];
            city_map_data.push(list);
        });
        var data = google.visualization.arrayToDataTable(city_map_data);

        var options = {};
        options['region'] = country_code;
        options['colors'] = [0xFF8747, 0xFFB581, 0xc06000]; //orange colors
        options['dataMode'] = 'markers';

        var container = document.getElementById('map_country');
        var geomap = new google.visualization.GeoMap(container);
        geomap.draw(data, options);
    };


    return AIRTIME;

}(AIRTIME || {}));


