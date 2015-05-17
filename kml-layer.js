/** Copyright 2013, Malte Reißig
 *  Written for navigating through the LOR-Pages.
 */

function showMapElements() {
    
}

function setupMapNavigation(date_string, lor_id) {

    // set initial map view to berlin city
    var bounds = new OpenLayers.Bounds()
        bounds.extend(new OpenLayers.LonLat(13.2023,52.344400))
        bounds.extend(new OpenLayers.LonLat(13.7423,52.642415))
        bounds.toBBOX()

    var mapbox_tiles = new OpenLayers.Layer.XYZ(
        "Mapbox LOR-Pages",
        [ "https://api.tiles.mapbox.com/v4/kiezatlas.m7222ia5/${z}/${x}/${y}.png?access_token=pk.eyJ1Ijoia2llemF0bGFzIiwiYSI6InFmRTdOWlUifQ.VjM4-2Ow6uuWR_7b49Y9Eg" ],
        {
            attribution: '&copy; <a href="https://www.mapbox.com/about/maps/"">MapBox</a> '
                + '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> '
                + '<a href="https://www.mapbox.com/map-feedback/">Improve this map</a>',
            sphericalMercator: true,
            wrapDateLine: true
        }
    );

    var map = new OpenLayers.Map("berlin-citymap", {
        controls: [
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoomBar(),
            new OpenLayers.Control.LayerSwitcher({'ascending':false}),
            new OpenLayers.Control.ScaleLine(),
            new OpenLayers.Control.Attribution(),
            new OpenLayers.Control.OverviewMap(),
            new OpenLayers.Control.KeyboardDefaults()
        ], layers: [ mapbox_tiles ],
        projection: new OpenLayers.Projection("EPSG:900913"), 
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        zoom: 12
    });

    var dStyle = new OpenLayers.Style( {
        strokeColor: "#4170D4", strokeWidth: 1, fillColor: "#efefef", fillOpacity: .5, //, cursor: "pointer"
    });

    var defaultStyle = OpenLayers.Util.applyDefaults( dStyle, OpenLayers.Feature.Vector.style["default"]);
    var dStyleMap = new OpenLayers.StyleMap({
        "default": defaultStyle,
        "select": { strokeWidth: 3, fill: 0,
            label : "${name}", fontSize: "12px", fontStyle: "bold",
            fontFamily: "Arial,Helvetica,sans-serif", fontColor: "#B60033"}
    });
    

    // projection: new OpenLayers.Projection("EPSG:900913") breaks the setup
    var lor = new OpenLayers.Layer.Vector("KML Layer", {
        styleMap: dStyleMap, projection: map.displayProjection,
        strategies: [ new OpenLayers.Strategy.Fixed() ],
        protocol: new OpenLayers.Protocol.HTTP({
            url: "2012/06/data/LOR-Planungsraeume.kml", 
            format: new OpenLayers.Format.KML({ 'extractAttributes': true })
        })
    })

    map.zoomToExtent( bounds.transform(map.displayProjection, map.projection) )
    // osm_tiles.addOptions({ maxExtent: bounds }, true)

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
            // selectLorMarker(lor_id, lor, select, map)
            console.log("load end.. ")
        }
    })

    map.addLayer(lor)
    lor.redraw()

    function onFeatureSelect(event) {
        var feature = event.feature
        var selectedFeature = feature
        console.log('loading ... ' + feature.fid)
        if (lor_id == "0" + feature['fid']) {
            // selected feature is current page, dont' render any outgoing link
        } else {
            var lorId = (parseInt(feature.fid) > 9052001) ? parseInt(feature.fid) : "0" + feature.fid
            var popup = new OpenLayers.Popup.Anchored("link", 
                feature.geometry.getBounds().getCenterLonLat(),
                new OpenLayers.Size(50,50),
                '<a href="/~bob/lor-pages/'+date_string+'/?lor=' + lorId+ '">' +feature.data['name']+ '</a>',
                null, false, function(e) { popup.destroy() }
            );
            popup.autoSize = true
            popup.panMapIfOutOfView = true;
            feature.popup = popup
            map.addPopup(popup);
        }
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
