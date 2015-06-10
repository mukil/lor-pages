/** Copyright 2013, Malte Reißig
 *  Written for navigating through the LOR-Pages.
 */

function showMapElements() {
    
}

function setup_chloropeth_map(date_string, age_group) {

    console.log("setting up setup_chloropeth_map", date_string, age_group)

    // set initial map view to berlin city
    var bounds = new OpenLayers.Bounds()
        bounds.extend(new OpenLayers.LonLat(13.2023,52.344400))
        bounds.extend(new OpenLayers.LonLat(13.7423,52.642415))
        bounds.toBBOX()

    var mapbox_tiles = new OpenLayers.Layer.XYZ (
        "Mapbox LOR-Pages",
        [ "https://api.tiles.mapbox.com/v4/kiezatlas.m7222ia5/${z}/${x}/${y}.png?access_token=pk.eyJ1Ijoia2llemF0bGFzIiwiYSI6InFmRTdOWlUifQ.VjM4-2Ow6uuWR_7b49Y9Eg" ],
        {
            attribution: '&copy; <a href="https://www.mapbox.com/about/maps/"">MapBox</a> '
                + '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> '
                + '<a href="https://www.mapbox.com/map-feedback/">Improve this map</a>',
            sphericalMercator: true,
            wrapDateLine: true
        }
    )

    var map = new OpenLayers.Map("berlin-citymap", {
        controls: [
            new OpenLayers.Control.Navigation(),
            // new OpenLayers.Control.PanZoomBar(),
            // new OpenLayers.Control.LayerSwitcher({'ascending':false}),
            new OpenLayers.Control.ScaleLine(),
            new OpenLayers.Control.Attribution(),
            // new OpenLayers.Control.OverviewMap(),
            new OpenLayers.Control.KeyboardDefaults()
        ], layers: [ mapbox_tiles ],
        restrictExtent: bounds,
        projection: new OpenLayers.Projection("EPSG:900913"), 
        displayProjection: new OpenLayers.Projection("EPSG:4326")
    })

    map.getNumZoomLevels = function() { return 15 }
    map.isValidZoomLevel = function(zoomLevel) {
        return ( (zoomLevel != null) &&
        (zoomLevel >= 10) && // set min level here, could read from property
        (zoomLevel < this.getNumZoomLevels()) );
    }

    /* var dStyle = new OpenLayers.Style( {
        strokeColor: "#4170D4", strokeWidth: 1, fillColor: "#efefef", fillOpacity: 1, //, cursor: "pointer"
    }) **/

    var dStyle = new OpenLayers.Style( {
        strokeColor: "#666", strokeWidth: 1, fillColor: "#000", fillOpacity: 1, //, cursor: "pointer"
    })

    var ruleBase = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN_OR_EQUAL_TO,
                    property: "averaged_value",
                    value: 0,
                }),
            symbolizer: { fillColor: "#fff", fillOpacity: 0.7 }
        })
    // #f1eef6
    var ruleLow = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 0.4,
                }),
            symbolizer: { fillColor: "#f1eef6", fillOpacity: 0.7 }
        })
    // #bdc9e1
    var ruleMiddle = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 1,
                }),
            symbolizer: { fillColor: "#bdc9e1", fillOpacity: 0.7 }
        })
    // #74a9cf
    var ruleHigh = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 1.5,
                }),
            symbolizer: { fillColor: "#74a9cf", fillOpacity: 0.7 }
        })
    // #2b8cbe
    var ruleTop = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 2,
                }),
            symbolizer: { fillColor: "#2b8cbe", fillOpacity: 0.7 }
        })
    // #045a8d
    var ruleOver = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 3,
                }),
            symbolizer: { fillColor: "#045a8d", fillOpacity: 0.7 }
        })
    var ruleOverTop = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 4,
                }),
            symbolizer: { fillColor: "#045a8d", fillOpacity: 0.9 }
        })
    var ruleMegaOver = new OpenLayers.Rule({
            filter: new OpenLayers.Filter.Comparison({
                    type: OpenLayers.Filter.Comparison.GREATER_THAN,
                    property: "averaged_value",
                    value: 5,
                }),
            symbolizer: { fillColor: "#343434", fillOpacity: 0.9 }
        })
    dStyle.addRules([ruleBase, ruleLow, ruleMiddle, ruleHigh, ruleTop, ruleOver, ruleOverTop, ruleMegaOver])

    var defaultStyle = OpenLayers.Util.applyDefaults( dStyle, OpenLayers.Feature.Vector.style["default"]);
    var dStyleMap = new OpenLayers.StyleMap({
        "default": defaultStyle,
        "select": { strokeWidth: 3, fill: 0,
            label : "${name}", fontSize: "12px", fontStyle: "bold",
            fontFamily: "Arial,Helvetica,sans-serif", fontColor: "#B60033"}
    })
    
    // projection: new OpenLayers.Projection("EPSG:900913") breaks the setup
    var lor = new OpenLayers.Layer.Vector("KML Layer", {
        styleMap: dStyleMap, projection: map.displayProjection,
        strategies: [ new OpenLayers.Strategy.Fixed() ],
        protocol: new OpenLayers.Protocol.HTTP({
            url: "2012/06/data/LOR-Planungsraeume-rev2.kml",
            format: new OpenLayers.Format.KML({ 'extractAttributes': true })
        })
    })

    map.zoomToExtent(bounds.transform(map.displayProjection, map.projection), 13 )

    var hover = new OpenLayers.Control.SelectFeature(lor, { renderIntent: "temporary", hover: true, highlightOnly: true })
    map.addControl(hover)
    hover.activate()

    var select = new OpenLayers.Control.SelectFeature(lor, { hover: false, highlightOnly: false })
    map.addControl(select)
    select.activate()

    lor.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect,
        "loadend": function(e) {

            var url = "/~malte/"+year_string+"/calculation.php?agegroup_id=" + age_group
            var lor_values;

            $.get(url, function (data) {

                lor_values = JSON.parse(data)

                for (var featureIdx in lor.features) {
                    var area = lor.features[featureIdx]
                    var percentage_value = getPercentageValue(area.fid)
                    var absolute_value = getAbsoluteValue(area.fid)
                    var averaged_value = getAveragedValue(area.fid)
                    // console.log("Area value set: " + area.fid, percentage_value)
                    area.attributes.percentage_value = percentage_value;
                    area.attributes.absolute_value = absolute_value;
                    area.attributes.averaged_value = averaged_value;
                }

                function getAveragedValue(fid) {
                    for (var idx in lor_values) {
                        var numericId = parseInt(lor_values[idx].lor_id)
                        if (numericId == fid) return lor_values[idx].averaged
                    }
                }

                function getPercentageValue(fid) {
                    for (var idx in lor_values) {
                        var numericId = parseInt(lor_values[idx].lor_id)
                        if (numericId == fid) return lor_values[idx].percentage
                    }
                }

                function getAbsoluteValue(fid) {
                    for (var idx in lor_values) {
                        var numericId = parseInt(lor_values[idx].lor_id)
                        if (numericId == fid) return lor_values[idx].total
                    }
                }

                lor.redraw()

            })

        }
    })

    map.addLayer(lor)
    lor.redraw()

    function onFeatureSelect(event) {
        var feature = event.feature
        var selectedFeature = feature
        // console.log('loading ... ', feature)
        var lorId = (parseInt(feature.fid) > 9052001) ? parseInt(feature.fid) : "0" + feature.fid
        var popup = new OpenLayers.Popup.Anchored("link", 
            feature.geometry.getBounds().getCenterLonLat(),
            new OpenLayers.Size(270, 72),
            '<em>Anzahl der gemeldeten Personen: </em><b>'+feature.attributes['absolute_value']+'</b><br/>'
            + '<em>Prozentsatz: </em><b>'+feature.attributes['percentage_value']+'%</b> '
            + '<em>LOR-Average: </em><b>'+feature.attributes['averaged_value']+'%</b><br/>'
            + '<a href="/~bob/lor-pages/'+date_string+'/?lor=' + lorId+ '">zur LOR-Seite \"' +feature.data['name']+ '\"</a><br/>',
            null, true, function(e) { popup.destroy() }
        );
        popup.panMapIfOutOfView = true;
        feature.popup = popup
        map.addPopup(popup);
    }

    function onFeatureUnselect(event) {
        var feature = event.feature;
        if (feature.popup) {
            map.removePopup(feature.popup)
            feature.popup.destroy()
            delete feature.popup
        }
    }

}

function selectLorMarker(lor_id, lor, select, map) {
    for (i=0; i < lor.features.length; i++) {
        var crt = lor.features[i]
        if (parseInt(crt['fid']) == lor_id) {
            select.select(crt)
            lor.redraw()
            map.zoomToExtent( crt.geometry.bounds )
            map.zoomTo(map.zoom-1)
            break
        }
    }
}
