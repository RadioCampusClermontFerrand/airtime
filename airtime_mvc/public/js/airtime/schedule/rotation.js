var AIRTIME = (function (AIRTIME) {
    var mod;

    if (AIRTIME.rotation === undefined) {
        AIRTIME.rotation = {};
    }

    mod = AIRTIME.rotation;

    var endpoint = '/rest/rotation';

    /**
     * RotationController constructor.
     *
     * @param {angular.scope}   $scope           angular scope service object
     * @param {angular.http}    $http            angular http service object
     *
     * @constructor
     */
    function RotationController($scope, $http) {
        var self = this;

        self._initTables();
        // TODO

        return self;
    }

    RotationController.prototype._initTables = function () {
        var buttons = AIRTIME.widgets.Table.getStandardToolbarButtons(),
            params = {
                sAjaxSource : endpoint,
                aoColumns: [
                    /* Title */ { "sTitle" : $.i18n._("Name"), "mDataProp" : "name", "sClass" : "rotation_name", "sWidth" : "170px" },
                    /* Minimum Track Length */ { "sTitle" : $.i18n._("Minimum Track Length"), "mDataProp" : "minimum_track_length", "sClass" : "rotation_minimum_track_length", "sWidth" : "170px" },
                    /* Maximum Track Length */ { "sTitle" : $.i18n._("Maximum Track Length"), "mDataProp" : "maximum_track_length", "sClass" : "rotation_maximum_track_length", "sWidth" : "170px" },
                    /* Playlist ID */ { "sTitle" : $.i18n._("Playlist"), "mDataProp" : "playlist", "sClass" : "rotation_playlist", "sWidth" : "80px" }
                ],
                fnDrawCallback: function () {
                    AIRTIME.library.drawEmptyPlaceholder(this);
                }
            };

        this.rotationTable = new AIRTIME.widgets.Table(
            $('#rotation_table'),
            true,
            buttons,
            params,
            {
                iconClass: "icon-white icon-refresh",
                html: $.i18n._("You haven't created any rotations!")
                + "<br/>" + $.i18n._("FIXME")
                + "<br/><a target='_parent' href=''>" + $.i18n._("Learn about Rotations") + "</a>"
            }
        );

        mod.rotationTable = this.rotationTable.getDatatable();
        mod.rotationTable.addTitles("td");

        params = {
            bServerSide : false,
            sAjaxSource : null,
            // Initialize the table with empty data so we can defer loading
            aaData      : {},
            aoColumns: [
                /* starts */ {"mDataProp": "starts", "sTitle": $.i18n._("Start"), "sClass": "sb-starts", "sWidth": "60px"},
                /* ends */ {"mDataProp": "ends", "sTitle": $.i18n._("End"), "sClass": "sb-ends", "sWidth": "60px"},
                /* runtime */ {"mDataProp": "runtime", "sTitle": $.i18n._("Duration"), "sClass": "library_length sb-length", "sWidth": "65px"},
                /* title */ {"mDataProp": "title", "sTitle": $.i18n._("Title"), "sClass": "sb-title"},
                /* creator */ {"mDataProp": "creator", "sTitle": $.i18n._("Creator"), "sClass": "sb-creator"},
                /* album */ {"mDataProp": "album", "sTitle": $.i18n._("Album"), "sClass": "sb-album"},
                /* cue in */ {"mDataProp": "cuein", "sTitle": $.i18n._("Cue In"), "bVisible": false, "sClass": "sb-cue-in"},
                /* cue out */ {"mDataProp": "cueout", "sTitle": $.i18n._("Cue Out"), "bVisible": false, "sClass": "sb-cue-out"},
                /* fade in */ {"mDataProp": "fadein", "sTitle": $.i18n._("Fade In"), "bVisible": false, "sClass": "sb-fade-in"},
                /* fade out */ {"mDataProp": "fadeout", "sTitle": $.i18n._("Fade Out"), "bVisible": false, "sClass": "sb-fade-out"},
                /* mime */  {"mDataProp" : "mime", "sTitle" : $.i18n._("Mime"), "bVisible": false, "sClass": "sb-mime"}
            ],
            fnDrawCallback: function () {
                AIRTIME.library.drawEmptyPlaceholder(this);
            }
        };

        this.previewTable = new AIRTIME.widgets.Table(
            $('#rotation_preview'),
            true,
            buttons,
            params,
            {
                iconClass: "icon-white icon-refresh",
                html: $.i18n._("Click the 'Generate' button to display a sample schedule for this Rotation")
                + "<br/>" + $.i18n._("FIXME")
                + "<br/><a target='_parent' href=''>" + $.i18n._("Learn about Rotations") + "</a>"
            }
        );

        mod.previewTable = this.previewTable.getDatatable();
        // Since we're using a static source, the first fnDraw call is made before the
        //   table is finished initializing. Redraw here so we get the empty placeholder
        mod.previewTable.fnDraw();
        mod.previewTable.addTitles("td");
    };

    mod.rotationApp = angular.module('rotation', [])
        .controller('Rotation', ['$scope', '$http', RotationController]);

    return AIRTIME;
}(AIRTIME || {}));

$(document).ready(function() {
    // AIRTIME.rotation.init();
});
