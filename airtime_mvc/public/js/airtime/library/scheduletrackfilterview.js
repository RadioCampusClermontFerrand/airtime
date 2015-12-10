/**
 * Created by asantoni on 09/12/15.
 */
var AIRTIME = (function(AIRTIME) {

    //Module initialization
    if (AIRTIME.ScheduleTrackFilterView === undefined) {
        AIRTIME.ScheduleTrackFilterView = {};
    }

    AIRTIME.ScheduleTrackFilterView.initialize = function(trackID, domNode)
    {
        var aoColumns = [
            /* Title */           { "sTitle" : $.i18n._("Track Start Time")              , "mDataProp" : "starts"  , "sClass"      : "library_title"       , "sWidth"      : "170px"                 },
            /* Creator */         { "sTitle" : $.i18n._("Track End Time")            , "mDataProp" : "ends"  , "sClass"      : "library_creator"     , "sWidth"      : "160px"                 },
            /* Upload Time */     { "sTitle" : $.i18n._("Show Start Time")           , "mDataProp" : "show_instance.starts"        , "bVisible"    : true    , "sWidth" : "155px"        },
            /* Upload Time */     { "sTitle" : $.i18n._("Show Name")           , "mDataProp" : "show.name"        , "sWidth" : "155px"        },

        ];

        var ajaxSourceURL = baseUrl+"rest/media/" + trackID + "/schedule";

        var myToolbarButtons = jQuery.extend(true, {}, [AIRTIME.widgets.Table._STANDARD_TOOLBAR_BUTTONS[AIRTIME.widgets.Table.TOOLBAR_BUTTON_ROLES.DELETE]]);
        myToolbarButtons[0].eventHandlers.click = function(e) { alert('Delete!'); };

        //Set up the div with id "example-table" as a datatable.
        var table = new AIRTIME.widgets.Table(
            domNode, //DOM node to create the table inside.
            true,                //Enable item selection
            myToolbarButtons,    //Toolbar buttons
            {                    //Datatables overrides.
                'aoColumns' : aoColumns,
                'sAjaxSource' : ajaxSourceURL
            });

    };

    return AIRTIME;

}(AIRTIME || {}));

