var api_data;

$(document).ready(function() {

    setupDateRangePicker();

    getAllReports();

    $("#back-to-world-map-btn").click(function() {
        $("#map_country").hide();
        $(this).hide();
        $("#map_world").show();
    });

    $("#stats-range-btn").click(getAllReports);

});

function getAllReports() {
    var start = $("#stats-start-date-picker").val();
    var end = $("#stats-end-date-picker").val();

    getGeolocationStats(start, end);
    getAggregateTuningMinutesStats(start, end);
}

function getAggregateTuningMinutesStats(start, end) {
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

function setupDateRangePicker() {
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

function getGeolocationStats(start, end) {
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
        drawWorldMap(country_map_data);
    });
}

function drawWorldMap(country_map_data) {
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

function drawCountryMap(country_code, cities) {
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