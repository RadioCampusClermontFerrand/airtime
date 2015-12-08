var api_data;

$(document).ready(function() {
    startGeolocationStats();

    $("#back-to-world-map-btn").click(function() {
        $("#map_country").hide();
        $(this).hide();
        $("#map_world").show();
    });
});

function startGeolocationStats() {
    var country_map_data = {};
    $.get("/rest/listener-stats/geolocation", {start: '2015-01-01 00:00:00', end: '2015-12-31 00:00:00'}, function(data) {
        api_data = JSON.parse(data);

        country_map_data = [];
        country_map_data.push(["Country", "Listeners"]);
        $.each(api_data, function(k,v) {
            var num_listeners = v.total
            var list = [k, num_listeners.toString()];
            country_map_data.push(list);
        });
        drawWorldMap(country_map_data);
    });
}

function drawWorldMap(country_map_data) {
    var data = google.visualization.arrayToDataTable(country_map_data);

    var options = {};
    options['dataMode'] = 'regions';

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