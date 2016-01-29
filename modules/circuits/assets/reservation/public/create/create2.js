//var meicanGMap = new MeicanGMap("map-canvas");
var meicanMap = new MeicanLMap('canvas');
//var meicanGraph = new MeicanGraph("map-canvas");
var meicanTopo = [];

var MARKER_OPTIONS_DEV = '' +
'<div><button style="font-size: 10px; height: 22px; width: 48.25%;" id="set-as-source">From here</button>' +
'<button style="font-size: 10px; height: 22px; width: 48.25%;" id="set-as-dest">To here</button></div><div style="height: 2px;"></div>' +
'<div><button style="font-size: 10px; height: 22px; width: 98%;" id="add-waypoint">Add waypoint</button>' +
'</div>';

$(document).ready(function() {
    meicanMap.show("rnp", 'dev');
    
    $("#add-waypoint").click(function() {
        $("#destination-client").before('<li class="path-point">'+
                    '<i class="fa fa-map-marker bg-gray"></i>'+
                    '<div class="timeline-item">'+
                        '<h3 class="timeline-header">'+
                            'none'+
                            '<div class="pull-right">'+
                                '<a href="#" class="text-muted"><i class="fa fa-minus"></i></a>'+
                                '<a href="#" class="text-muted" style="margin-left: 3px;"><i class="fa fa-arrow-up"></i></a>'+
                                '<a href="#" class="text-muted" style="margin-left: 3px;"><i class="fa fa-arrow-down"></i></a>'+
                            '</div>'+
                        '</h3>'+
                        '<div class="timeline-body">'+
                              'Network<br>Device<br>Port<br>VLAN'+
                              '<div class="pull-right">'+
                                    '<a href="#" class="text-muted"><i class="fa fa-pencil"></i></a>'+
                                    '<a href="#" class="text-muted" style="margin-left: 3px;"><i class="fa fa-trash"></i></a>'+
                                '</div>'+
                            '</div>'+
                    '</div>'+
                '</li>');
        return false;
    });

    $('#canvas').on('markerClick', function(e, marker) {
        marker.setPopupContent('Domain: cipo.rnp.br<br>Device: POA<br><br><div class="btn-group">'+
            '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'+
              'Options <span class="caret"></span>'+
            '</button>'+
            '<ul class="dropdown-menu">'+
              '<li><a class="set-source" href="#">From here</a></li>'+
              '<li><a class="add-waypoint" href="#">Add waypoint</a></li>'+
              '<li><a class="set-destination" href="#">To here</a></li>'+
            '</ul>'+
          '</div>');

        setPoint(0, marker);
    });

    $(".fa-arrow-down").click(function() {
    });

    $("#path").on('click','.fa-trash', function() {
        $(this).parent().parent().parent().parent().parent().remove();
        return false;
    });

    $("#path").on('click','.fa-minus', function() {
        $(this).removeClass('fa-minus');
        $(this).addClass('fa-plus');
        $(this).parent().parent().parent().parent().find('.timeline-body').slideUp();
        return false;
    });

    $("#path").on('click','.fa-plus',function() {
        $(this).removeClass('fa-plus');
        $(this).addClass('fa-minus');
        $(this).parent().parent().parent().parent().find('.timeline-body').slideDown();
        return false;
    });

    $(".sidebar-mini").addClass("sidebar-collapse");
    //meicanGraph.build("graph-canvas");
    
    //$(".main-footer").hide();
    $("#canvas").css("height", $(window).height() - 50);
    if($(window).width() < 768) {
        $("#canvas").css("width", $(window).width() - 40);
    } else {
        $("#canvas").css("width", $(window).width() - 51);
    }
    //$("#canvas").css("width", $(window).width() - 51);
    var lsidebar = L.control.lsidebar('lsidebar').addTo(meicanMap.getMap());

    $( window ).resize(function() {
        $("#canvas").css("height", $(window).height() - 50);
        if($(window).width() < 768) {
            $("#canvas").css("width", $(window).width() - 40);
        } else {
            $("#canvas").css("width", $(window).width() - 51);
        }
    })
    
    /*$( "#graph-canvas" ).on( "nodeClick", function(event, nodeId) {
        meicanMap.showMarker(nodeId);
    });
    $( "#map-canvas" ).on( "markerClick", function(event, markerId) {
        meicanGraph.showNode(markerId);
    });*/
        
    loadDomains();
});

function setPoint(position, marker) {
    $($(".path-point")[position]).find('.point-net').text('glo');
}

/*$("#viewer-mode-select").selectmenu({
    select: function( event, ui ) {
        switch(ui.item.value) {
            case "mg-s" : 
                meicanLMap.hide();
                meicanGraph.hide();
                meicanGMap.show('s', $("#node-type-select").val());
                break;
            case "ml-osm" :
                meicanGMap.hide();
                meicanGraph.hide();
                meicanLMap.show("osm", $("#node-type-select").val());
                break;
            case "ml-mq" :
                meicanGMap.hide();
                meicanGraph.hide();
                meicanLMap.show("mq");
                break;
            case "gv" : 
                meicanLMap.hide();
                meicanGMap.hide();
                meicanGraph.show();
                break;    
        }
    }
});    

$("#node-type-select").selectmenu({
    select: function( event, ui ) {
        switch($("#viewer-mode-select").val()) {
            case "ml-osm":
                meicanLMap.show('osm', ui.item.value);
                break;
        }

        switch(ui.item.value) {
            case "net" : 
                loadNetworks();
                break;  
            case "dev" : 
                loadDevices();
                break;
            case "port" : 
                loadPorts();
                break;  
            case "prov" : 
                loadProviders();
                break;   
        }
    }
});

$("#save-button").on("click", function(){
    meicanGraph._graph.storePositions();
    $.ajax({
        type: "POST",
        url: baseUrl + '/topology/viewer/save-graph',
        data: {
            mode: $("#node-type-select").val(),
            nodes: meicanGraph._nodes.get({
                filter: function (item) {
                    return item.type == $("#node-type-select").val();
                }
            })
        },
        success: function (response) {
        },
        error: function() {
        }
    });
});*/

function loadDomains() {
    $.ajax({
        url: baseUrl+'/topology/domain/get-all',
        dataType: 'json',
        method: "GET",        
        success: function(response) {
            meicanTopo['dom'] = response;
            meicanMap.setDomains(response);
            //meicanLMap.setDomains(response);
            //meicanGMap.setDomains(response);
            //meicanMap.setDomains(response);
            /*meicanGraph.setDomains(response);
            meicanGraph.addNodes(response, "dom", true);
            meicanGraph.fit();*/
            loadDevices();
        }
    });
}

function loadDevices() {
    if(meicanTopo['dev']) return;
    $.ajax({
        url: baseUrl+'/topology/device/get-all',
        dataType: 'json',
        method: "GET",
        data: {
            cols: JSON.stringify(['id','name','latitude','longitude','graph_x', 'graph_y', 'domain_id'])
        },
        success: function(response) {
            meicanTopo['dev'] = true;
            //meicanGraph.addNodes(response, 'dev', true);
            for (var i = 0; i < response.length; i++) {
                meicanMap.addMarker(response[i], 'dev');
            };/*
            meicanGraph.fit();
            loadDeviceLinks();*/
        }
    });
}

function loadPorts() {
    if(meicanTopo['port']) return;
    $.ajax({
        url: baseUrl+'/topology/port/get-all-bidirectional',
        dataType: 'json',
        method: "GET",
        data: {
            cols: JSON.stringify(['id','name','device_id'])
        },
        success: function(response) {
            meicanTopo['port'] = true;
            meicanGraph.addNodes(response, 'port', true);
            meicanGraph.fit();
            loadPortLinks();
        }
    });
}

function loadNetworks() {
    if(meicanTopo['net']) return;
    $.ajax({
        url: baseUrl+'/topology/network/get-all',
        dataType: 'json',
        method: "GET",
        data: {
            cols: JSON.stringify(['id','name','latitude','longitude', 'domain_id'])
        },
        success: function(response) {
            meicanTopo['net'] = true;
            meicanGraph.addNodes(response, 'net');
            for (var i = 0; i < response.length; i++) {
                meicanMap.addMarker(response[i], 'net');
            };
            loadNetworkLinks();
        }
    });
}

function loadProviders() {
    if(meicanTopo['prov']) return;
    $.ajax({
        url: baseUrl+'/topology/provider/get-all',
        dataType: 'json',
        method: "GET",
        data: {
            cols: JSON.stringify(['id','name','latitude','longitude', 'domain_id'])
        },
        success: function(response) {
            meicanTopo['prov'] = response;
            /*meicanGraph.addNodes(response, 'prov');
            for (var i = 0; i < response.length; i++) {
                meicanMap.addMarker(response[i], 'prov');
            };*/
            loadProviderLinks();
        }
    });
}

function loadDomainLinks() {
    $.ajax({
        url: baseUrl+'/topology/viewer/get-domain-links',
        dataType: 'json',
        method: "GET",
        success: function(response) {
            meicanTopo['doml'] = response;
            //meicanGraph.addLinks(response, 'dom');            
        }
    });
}

function loadProviderLinks() {
    $.ajax({
        url: baseUrl+'/topology/viewer/get-peerings',
        dataType: 'json',
        method: "GET",
        success: function(response) {
            meicanGraph.addLinks(response, 'prov');
            for (var src in response) {
                for (var i = 0; i < response[src].length; i++) {
                    meicanMap.addLink('prov'+src,'prov'+response[src][i], 'prov');
                }
            }
        }
    });
}

function loadNetworkLinks() {
    $.ajax({
        url: baseUrl+'/topology/viewer/get-network-links',
        dataType: 'json',
        method: "GET",
        success: function(response) {
            meicanGraph.addLinks(response, 'net');
            for (var src in response) {
                for (var i = 0; i < response[src].length; i++) {
                    meicanMap.addLink('net'+src,'net'+response[src][i], 'net');
                }
            }           
        }
    });
}

function loadDeviceLinks() {
    $.ajax({
        url: baseUrl+'/topology/viewer/get-device-links',
        dataType: 'json',
        method: "GET",
        success: function(response) {
            meicanGraph.addLinks(response, 'dev');
            for (var src in response) {
                for (var i = 0; i < response[src].length; i++) {
                    meicanMap.addLink('dev'+src,'dev'+response[src][i], 'dev');
                }
            }           
        }
    });
}

function loadPortLinks() {
    $.ajax({
        url: baseUrl+'/topology/viewer/get-port-links',
        dataType: 'json',
        method: "GET",
        success: function(response) {
            meicanGraph.addLinks(response, 'port');
        }
    });
}


