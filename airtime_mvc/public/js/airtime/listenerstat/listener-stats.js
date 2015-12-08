var api_data;

$(document).ready(function() {
    startGeolocationStats();

    $("#back-to-world-map-btn").click(function() {
        $("#map_country").hide();
        $(this).hide();
        $("#map_world").show();
    });

    $("#geo-stat-range-btn").click(getGeolocationStats);
});

function startGeolocationStats() {
    setupDateRangePicker();
    getGeolocationStats();
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

    oBaseTimePickerSettings = {
        showPeriodLabels: false,
        showCloseButton: true,
        closeButtonText: $.i18n._("Done"),
        showLeadingZero: false,
        defaultTime: '0:00',
        hourText: $.i18n._("Hour"),
        minuteText: $.i18n._("Minute")
    };
    var d = new Date();
    d.setDate(d.getDate()-1);
    $("#geo-stat-start-date-picker").val($.datepicker.formatDate("yy-m-dd", d));
    $("#geo-stat-start-date-picker").datepicker(oBaseDatePickerSettings);

    $("#geo-stat-start-time-picker").val("00:00");
    $("#geo-stat-start-time-picker").timepicker(oBaseTimePickerSettings);

    d.setDate(d.getDate()+1);
    $("#geo-stat-end-date-picker").val($.datepicker.formatDate("yy-m-dd", d));
    $("#geo-stat-end-date-picker").datepicker(oBaseDatePickerSettings);

    $("#geo-stat-end-time-picker").val("00:00");
    $("#geo-stat-end-time-picker").timepicker(oBaseTimePickerSettings);
}

function getGeolocationStats() {
    $("#back-to-world-map-btn").click();

    var start = $("#geo-stat-start-date-picker").val() + " " + $("#geo-stat-start-time-picker").val() + ":00";
    var end = $("#geo-stat-end-date-picker").val() + " " + $("#geo-stat-end-time-picker").val() + ":00";

    $.get("/rest/listener-stats/geolocation", {start: start, end: end}, function(data) {
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