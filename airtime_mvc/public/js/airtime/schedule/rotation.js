var AIRTIME = (function (AIRTIME) {
    var mod;

    if (AIRTIME.rotation === undefined) {
        AIRTIME.rotation = {};
    }

    mod = AIRTIME.rotation;

    var endpoint = '/rest/rotation/';

    /**
     *
     *
     * @param {angular.scope}   $scope           angular scope service object
     * @param {angular.http}    $http            angular http service object
     *
     * @constructor
     */
    function RotationController($scope, $http) {
        var self = this;
        $scope.rotation = {};

        $scope.post = function () {
            $http.post(endpoint, $scope.rotation)
                .success(function () {
                    // todo
                    console.log("Rotation successfully posted");
                });
        };

        $scope.get = function () {
            $http.get(endpoint + $scope.rotation.id)
                .success(function () {
                    // todo
                    console.log("Rotation successfully retrieved");
                });
        };

        $scope.put = function () {
            $http.put(endpoint + $scope.rotation.id)
                .success(function () {
                    // todo
                    console.log("Rotation successfully updated");
                });
        };

        $scope.delete = function () {
            $http.delete(endpoint + $scope.rotation.id)
                .success(function () {
                    // todo
                    console.log("Rotation successfully deleted");
                });
        };

        $scope.initTables = self._initTables;

        return self;
    }

    RotationController.prototype._initTables = function () {
        var buttons = AIRTIME.widgets.Table.getStandardToolbarButtons(),
            params = {
                sAjaxSource : endpoint,
                aoColumns: [
                    /* Title */                { "sTitle" : $.i18n._("Name"), "mDataProp" : "name", "sClass" : "rotation_name", "sWidth" : "170px" },
                    /* Playlist ID */          { "sTitle" : $.i18n._("Playlist"), "mDataProp" : "playlist", "sClass" : "rotation_playlist", "sWidth" : "80px" }
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
            sDom: '<"dt-process-rel"r><"H"<"table_toolbar"C>><"dataTables_scrolling"t<".empty_placeholder"<".empty_placeholder_image"><".empty_placeholder_text">>>',
            bSort: false,
            bServerSide : false,
            sAjaxSource : null,
            // Initialize the table with empty data so we can defer loading
            aaData      : {},
            aoColumns: [
                /* Title */           { "sTitle" : $.i18n._("Title")              , "mDataProp" : "track_title"  , "sClass"      : "rotation_preview_title"       , "sWidth"      : "170px"                 },
                /* Creator */         { "sTitle" : $.i18n._("Creator")            , "mDataProp" : "artist_name"  , "sClass"      : "rotation_preview_creator"     , "sWidth"      : "160px"                 },
                /* Album */           { "sTitle" : $.i18n._("Album")              , "mDataProp" : "album_title"  , "sClass"      : "rotation_preview_album"       , "sWidth"      : "150px"                 },
                /* Bit Rate */        { "sTitle" : $.i18n._("Bit Rate")           , "mDataProp" : "bit_rate"     , "bVisible"    : false                 , "sClass"      : "rotation_preview_bitrate"       , "sWidth" : "80px"         },
                /* BPM */             { "sTitle" : $.i18n._("BPM")                , "mDataProp" : "bpm"          , "bVisible"    : false                 , "sClass"      : "rotation_preview_bpm"           , "sWidth" : "50px"         },
                /* Composer */        { "sTitle" : $.i18n._("Composer")           , "mDataProp" : "composer"     , "bVisible"    : false                 , "sClass"      : "rotation_preview_composer"      , "sWidth" : "150px"        },
                /* Conductor */       { "sTitle" : $.i18n._("Conductor")          , "mDataProp" : "conductor"    , "bVisible"    : false                 , "sClass"      : "rotation_preview_conductor"     , "sWidth" : "125px"        },
                /* Copyright */       { "sTitle" : $.i18n._("Copyright")          , "mDataProp" : "copyright"    , "bVisible"    : false                 , "sClass"      : "rotation_preview_copyright"     , "sWidth" : "125px"        },
                /* Cue In */          { "sTitle" : $.i18n._("Cue In")             , "mDataProp" : "cuein"        , "bVisible"    : false                 , "sClass"      : "rotation_preview_length"        , "sWidth" : "80px"         },
                /* Cue Out */         { "sTitle" : $.i18n._("Cue Out")            , "mDataProp" : "cueout"       , "bVisible"    : false                 , "sClass"      : "rotation_preview_length"        , "sWidth" : "80px"         },
                /* Description */     { "sTitle" : $.i18n._("Description")        , "mDataProp" : "description"  , "bVisible"    : false                 , "sClass"      : "rotation_preview_description"   , "sWidth" : "150px"        },
                /* Encoded */         { "sTitle" : $.i18n._("Encoded By")         , "mDataProp" : "encoded_by"   , "bVisible"    : false                 , "sClass"      : "rotation_preview_encoded"       , "sWidth" : "150px"        },
                /* Genre */           { "sTitle" : $.i18n._("Genre")              , "mDataProp" : "genre"        , "bVisible"    : false                 , "sClass"      : "rotation_preview_genre"         , "sWidth" : "100px"        },
                /* ISRC Number */     { "sTitle" : $.i18n._("ISRC")               , "mDataProp" : "isrc_number"  , "bVisible"    : false                 , "sClass"      : "rotation_preview_isrc"          , "sWidth" : "150px"        },
                /* Label */           { "sTitle" : $.i18n._("Label")              , "mDataProp" : "label"        , "bVisible"    : false                 , "sClass"      : "rotation_preview_label"         , "sWidth" : "125px"        },
                /* Language */        { "sTitle" : $.i18n._("Language")           , "mDataProp" : "language"     , "bVisible"    : false                 , "sClass"      : "rotation_preview_language"      , "sWidth" : "125px"        },
                /* Last Modified */   { "sTitle" : $.i18n._("Last Modified")      , "mDataProp" : "mtime"        , "bVisible"    : false                 , "sClass"      : "rotation_preview_modified_time" , "sWidth" : "155px"        },
                /* Last Played */     { "sTitle" : $.i18n._("Last Played")        , "mDataProp" : "lptime"       , "bVisible"    : false                 , "sClass"      : "rotation_preview_modified_time" , "sWidth" : "155px"        },
                /* Length */          { "sTitle" : $.i18n._("Length")             , "mDataProp" : "length"       , "sClass"      : "rotation_preview_length"      , "sWidth"      : "80px"                  },
                /* Mime */            { "sTitle" : $.i18n._("Mime")               , "mDataProp" : "mime"         , "bVisible"    : false                 , "sClass"      : "rotation_preview_mime"          , "sWidth" : "80px"         },
                /* Mood */            { "sTitle" : $.i18n._("Mood")               , "mDataProp" : "mood"         , "bVisible"    : false                 , "sClass"      : "rotation_preview_mood"          , "sWidth" : "70px"         },
                /* Owner */           { "sTitle" : $.i18n._("Owner")              , "mDataProp" : "owner_id"     , "bVisible"    : false                 , "sClass"      : "rotation_preview_language"      , "sWidth" : "125px"        },
                /* Replay Gain */     { "sTitle" : $.i18n._("Replay Gain")        , "mDataProp" : "replay_gain"  , "bVisible"    : false                 , "sClass"      : "rotation_preview_replay_gain"   , "sWidth" : "125px"        },
                /* Sample Rate */     { "sTitle" : $.i18n._("Sample Rate")        , "mDataProp" : "sample_rate"  , "bVisible"    : false                 , "sClass"      : "rotation_preview_sr"            , "sWidth" : "125px"        },
                /* Track Number */    { "sTitle" : $.i18n._("Track Number")       , "mDataProp" : "track_number" , "bVisible"    : false                 , "sClass"      : "rotation_preview_track"         , "sWidth" : "125px"        },
                /* Upload Time */     { "sTitle" : $.i18n._("Uploaded")           , "mDataProp" : "utime"        , "bVisible"    : false                 , "sClass"      : "rotation_preview_upload_time"   , "sWidth" : "155px"        },
                /* Website */         { "sTitle" : $.i18n._("Website")            , "mDataProp" : "info_url"     , "bVisible"    : false                 , "sClass"      : "rotation_preview_url"           , "sWidth" : "150px"        },
                /* Year */            { "sTitle" : $.i18n._("Year")               , "mDataProp" : "year"         , "bVisible"    : false                 , "sClass"      : "rotation_preview_year"          , "sWidth" : "60px"         }
            ],
            fnDrawCallback: function () {
                AIRTIME.library.drawEmptyPlaceholder(this);
            }
        };

        this.previewTable = new AIRTIME.widgets.Table(
            $('#rotation_preview'),
            false,  // Selection
            {       // Buttons
                slideToggle: {
                    title           : '',
                    iconClass       : 'spl-no-r-margin icon-chevron-up',
                    extraBtnClass   : 'toggle-editor-form',
                    elementId       : '',
                    eventHandlers   : {},
                    validateConstraints: function () { return true; }
                },
                backBtn: {
                    title           : $.i18n._('Generate'),
                    iconClass       : 'icon-refresh',
                    extraBtnClass   : '',
                    elementId       : '',
                    eventHandlers   : {
                        click: function () {
                            mod._generateRotationPreview();
                        }
                    },
                    validateConstraints: function () { return true; }
                }
            },
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

    mod._generateRotationPreview = function () {
        // TODO: make this dynamic (need to implement rotation selection)
        $.get(endpoint + 1 + '/preview', function (data) {
            var dt = mod.previewTable;
            dt.fnClearTable(false);
            dt.fnAddData(JSON.parse(data));
        });
    };

    mod.rotationApp = angular.module('rotation', [])
        .controller('Rotation', ['$scope', '$http', RotationController])
        // onReady attribute to defer table instantiation; from http://stackoverflow.com/a/23717845
        .directive('ngElementReady', [function() {
            return {
                priority: -1000, // a low number so this directive loads after all other directives have loaded.
                restrict: "A", // attribute only
                link: function($scope, $element, $attributes) {
                    $scope.$eval($attributes.ngElementReady);
                }
            };
        }]);

    return AIRTIME;
}(AIRTIME || {}));

$(document).ready(function() {
    $("#rotations").on("click", ".toggle-editor-form", function(e) {
        // TODO
        $(this).closest(".content-pane").find(".inner_editor_wrapper").slideToggle(200);
        var buttonIcon = $(this).find('.icon-white');
        buttonIcon.toggleClass('icon-chevron-up');
        buttonIcon.toggleClass('icon-chevron-down');
    });
});
