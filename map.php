<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>SDATS Dialektkarten</title>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" integrity="sha512-gc3xjCmIy673V6MyOAZhIW93xhM9ei1I+gLbmFjUHIjocENRsLX/QUE1htk5q1XV2D/iie/VQ8DXI6Vu8bexvQ==" crossorigin="anonymous" /> -->
	<!-- <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js" integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw==" crossorigin="anonymous"></script> -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->
	<!-- <script src="https://cdn.tutorialjinni.com/leaflet.freedraw/2.0.1/leaflet-freedraw.web.js"></script> -->

    <style>
		* {
			box-sizing: border-box;
		}

        .html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        html {
            font-family: sans-serif;
        }

        .calculation-box {
            width: 200px;
            position: absolute;
            bottom: 40px;
            left: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 16px;
            text-align: center;
        }

		.leaflet-div-icon {
			width: 13px  !important;
			height: 13px !important;
			border: 2px solid #95bc59;
			border-radius: 50%;
		}

		.leaflet-num-icon {
			background: #95bc59;
			border: 1px solid rgba(0,0,0,0.2);
			border-radius: 50%;
		}

		.leaflet-marker-icon .number {
			position: relative;
			top: 1px;
			font-size: 14px;
			text-align: center;
			color: white;
		}

		.panel {
			display: flex;
			justify-content: center;
 			align-items: center;
			position: absolute;
			top: 10px;
			left: 10px;
			height: 26px;
			background: white;
			padding: 0 0 0 6px;
			box-shadow: 0 1px 5px rgba(0,0,0,0.65);
    		border-radius: 4px;
			z-index: 100;
		}

		#map {
			z-index: 10;
		}

		.log-out {
			border: none;
			height: 26px;
			padding: 6px;
			margin-left: 6px;
			background: none;
			line-height: 13px;
			text-decoration: none;
			color: #0055ff;
		}

		.log-out:hover {
			background: rgba(180,0,0, 0.1);
			color: #cc0000;
		}

				
		.map.mode-create {
			cursor: crosshair;
		}

		.leaflet-edge {
			background-color: #95bc59;
			box-shadow: 0 0 0 2px white, 0 0 10px rgba(0, 0, 0, .35);
			border-radius: 50%;
			cursor: move;
			outline: none;
			transition: background-color .25s;
		}

		.leaflet-polygon {
			fill: #b4cd8a;
			stroke: #50622b;
			stroke-width: 2;
			fill-opacity: .75;
		}
        
    </style>
</head>

<body>
	<div class="panel">
		Eingeloggt als&nbsp;<b><?=$_GET['p']?></b>
		<a href='/index.php?map' class='log-out'>Ausloggen</a>
	</div>
    <div id='map' style='width: 100vw; height: 100vh;'></div>

    <script>
		// var map = L.map('map', { zoomControl: false }).setView([47.3686498, 8.5391825], 11);

		// L.tileLayer('https://api.mapbox.com/styles/v1/tepertopogacsa/cist54zt3001r2xki2pegyq0f/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoidGVwZXJ0b3BvZ2Fjc2EiLCJhIjoiY2lzdDUydXF5MDAyMzJ0cGJsYzMxcTI3YyJ9.7jIF4sqbLn4FvB-6EQO6Tw', {
		// 	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		// 	maxZoom: 18,
		// 	tileSize: 512,
		// 	zoomOffset: -1,
		// 	accessToken: 'pk.eyJ1IjoidGVwZXJ0b3BvZ2Fjc2EiLCJhIjoiY2lzdDUydXF5MDAyMzJ0cGJsYzMxcTI3YyJ9.7jIF4sqbLn4FvB-6EQO6Tw'
		// }).addTo(map);

		// map.dragging.disable();
		// map.touchZoom.disable();
		// map.doubleClickZoom.disable();
		// map.scrollWheelZoom.disable();
		// map.boxZoom.disable();
		// map.keyboard.disable();

		// const freeDraw = new FreeDraw();

		// map.addLayer(freeDraw);

        
		// var drawPluginOptions = {
		// 	position: 'topright',
		// 	draw: {
		// 		polygon: {
		// 			allowIntersection: false,
		// 			drawError: {
		// 				color: '#ff0000',
		// 				showArea: true,
		// 				clickable: true
		// 			},
		// 			metric: true,
		// 			shapeOptions: {
		// 				color: '#0055ff'
		// 			}
		// 		},
		// 		polyline: false,
		// 		circle: false,
		// 		rectangle: false,
		// 		marker: false,
		// 		circlemarker: false,
		// 	},
		// 	edit: {
		// 		featureGroup: editableLayers,
		// 		remove: true
		// 	}
		// };

		// var drawControl = new L.Control.Draw(drawPluginOptions);
		// map.addControl(drawControl);

		// var geojson = null;
		// axios.post('/get.php', {
		// 		folder: '<?=$_GET['p']?>'
		// 	})
		// 	.then(function (response) {
		// 		if(response.data){
		// 			geojson = response.data;
		// 		}
		// 		L.geoJson(geojson, {
		// 			onEachFeature: function (feature, layer) {
		// 				editableLayers.addLayer(layer);
		// 			}
		// 		});
		// 		updateNumbers();
		// 		console.log(response);
		// 	})
		// 	.catch(function (error) {
		// 		console.log(error);
		// 	});

		// L.NumberedDivIcon = L.Icon.extend({
		// 	options: {
		// 		number: '',
		// 		iconSize: [24, 24],
		// 		iconAnchor: [12, 12],
		// 		popupAnchor: [0, -8],
		// 		className: 'leaflet-num-icon'
		// 	},

		// 	createIcon: function () {
		// 		var div = document.createElement('div');
		// 		var numdiv = document.createElement('div');
		// 		numdiv.setAttribute ( "class", "number" );
		// 		numdiv.innerHTML = this.options['number'] || '';
		// 		div.appendChild ( numdiv );
		// 		this._setIconStyles(div, 'icon');
		// 		return div;
		// 	},

		// 	//you could change this to add a shadow like in the normal marker if you really wanted
		// 	createShadow: function () {
		// 		return null;
		// 	}
		// });

		// map.on('draw:created', function(e){
		// 	var layer = e.layer;

		// 	var feature = layer.feature = layer.feature || {};
		// 	feature.type = feature.type || "Feature";
		// 	var props = feature.properties = feature.properties || {};

		// 	var arr = []
		// 	editableLayers.eachLayer(function (layer) {
		// 		arr.push(layer.feature.properties.number)
		// 	});

		// 	arr.sort();

		// 	if(!arr.length || arr[0] !== 1) {
		// 		props.number = 1;
		// 	} else {
		// 		for (var i = 0; i < arr.length; i++) {
		// 			if(arr[i] !== arr[i+1] - 1){
		// 				props.number = arr[i] + 1;
		// 				break;
		// 			}
		// 		}
		// 	}

		// 	editableLayers.addLayer(layer);
		// 	update(e);
		// 	updateNumbers();
		// });
		// map.on('draw:deleted', function(e){
		// 	updateNumbers();
		// 	update(e);
		// });
		// map.on('draw:edited', function(e){
		// 	updateNumbers();
		// 	update(e);
		// });

		// function updateNumbers(){
		// 	numberLayers.clearLayers();	
		// 	var i = 1;
		// 	editableLayers.eachLayer(function (layer) {

		// 		var marker = new L.Marker(layer.getBounds().getCenter(), {
		// 			icon:	new L.NumberedDivIcon({number: layer.feature.properties.number})
		// 		});	
		// 		numberLayers.addLayer(marker);
		// 	});
		// }

		// function update(e) {
		// 	geojson = {
		// 		"type": "FeatureCollection",
		// 		"features": []
		// 	};

		// 	// Iterate the layers of the map
		// 	map.eachLayer(function (layer) {
		// 		// Check if layer is a marker
		// 		if (layer instanceof L.Polygon) {
		// 			// Create GeoJSON object from marker
		// 			var shape = layer.toGeoJSON();
		// 			// Push GeoJSON object to collection
		// 			geojson.features.push(shape);
		// 		}
		// 	});

  		// 	var shapesString = JSON.stringify(geojson);

		// 	axios.post('/update.php', {
		// 		geo: shapesString,
		// 		folder: '<?=$_GET['p']?>'
		// 	})
		// 	.then(function (response) {
		// 		console.log(response);
		// 	})
		// 	.catch(function (error) {
		// 		console.log(error);
		// 	});
		// }

    </script>
	<script src="/main.js"></script>
</body>

</html>