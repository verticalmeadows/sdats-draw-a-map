import L from "leaflet";
import FreeDraw from 'leaflet-freedraw';
import axios from 'axios';

var map = L.map('map', { keyboard: true, keyboardPanDelta: 50, zoomControl: false }).setView([52.374500,9.738500],11);

L.tileLayer('https://api.mapbox.com/styles/v1/sehrlich/ckm4ljrntd81f17pta9zfhrgz/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2VocmxpY2giLCJhIjoiY2tndW01bzNhMDRkMjJ4cXQxNnhjNjdvYyJ9.faVqrChJdiEjZ--apFdrcA', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    tileSize: 512,
    zoomOffset: -1,
    detectRetina: true,
    styleLayer: 'mapbox://styles/sehrlich/ckm4ljrntd81f17pta9zfhrgz',
    accessToken: 'pk.eyJ1Ijoic2VocmxpY2giLCJhIjoiY2tndW01bzNhMDRkMjJ4cXQxNnhjNjdvYyJ9.faVqrChJdiEjZ—apFdrcA'
}).addTo(map);

L.control.zoom({
    position: 'bottomleft'
}).addTo(map);

/*
map.touchZoom.disable();
map.doubleClickZoom.disable();
map.scrollWheelZoom.disable();
map.boxZoom.disable();
*/
map.keyboard.enable();

const freeDraw = new FreeDraw({ mergePolygons: false });
var pList = [];
var lastDeleted = null;
const restore = document.querySelector('#restore');

function addItem(item) {
    restore.classList.add("hide");
    console.log("add item")
    let nums = []
    if (lastDeleted != null && JSON.stringify(item) == JSON.stringify(lastDeleted[0])) {
        let found = false;
        for (let i = 0; i < pList.length; i++) {
            if (pList[i][1] == lastDeleted[1]) {
                found = true;
                break;
            }
        }
        if (!found) {
            pList.push([item, lastDeleted[1]]);
            lastDeleted = null;
            return;
        }
    }
    for (let i = 0; i < pList.length; i++) {
        nums.push(pList[i][1]);
    }
    nums.sort((a, b) => a - b);
    console.log(nums)
    if (nums[0] != 0) {
        pList.push([item, 0])
        return;
    }
    for (let i = 0; i < nums.length; i++) {
        if (nums[i] != nums[i + 1] - 1) {
            pList.push([item, nums[i] + 1])
            return;
        }
    }
}

function updateList(newList) {
    console.log("update")
    if (pList.length == 0) {
        pList = [[newList[0], 0]];
    } else if (newList.length > pList.length) {
        addItem(newList[newList.length - 1]);
        return;
    } else if (newList.length < pList.length) {
        console.log("remove item")
        var tempList = [];

        for (let i = 0; i < newList.length; i++) {
            tempList[i] = JSON.stringify(newList[i]);
        }

        for (let i = 0; i < pList.length; i++) {
            const json = JSON.stringify(pList[i][0]);
            const has = !~tempList.indexOf(json);

            if (has) {
                lastDeleted = pList[i];
                restore.classList.remove("hide");
                pList.splice(i, 1);
                return;
            }
        }
    } else {
        console.log("update item")
        var tempList = [];
        var indexes = [];
        var newEl;

        for (let i = 0; i < pList.length; i++) {
            tempList[i] = JSON.stringify(pList[i][0]);
        }
        for (let i = 0; i < newList.length; i++) {
            const json = JSON.stringify(newList[i]);
            const index = tempList.indexOf(json);

            if (index != -1) {
                indexes.push(index);
            } else {
                newEl = newList[i];
            }
        }
        indexes.sort((a, b) => a - b);
        for (let i = 0; i < indexes.length; i++) {
            if (indexes[i + 1] != undefined && indexes[i] != indexes[i + 1] - 1) {
                pList[indexes[i] + 1][0] = newEl;
                return;
            } else if (indexes[i + 1] == undefined) {
                pList[indexes[i] + 1][0] = newEl;
                return;
            }
        }
    }
}

var allowSend = false;
freeDraw.on('markers', function (event) {
    if (!allowSend) {
        return
    }
    // console.log(event.latLngs)
    updateList(event.latLngs);
    console.log(pList)

    // console.log(event)
    // console.log(freeDraw.all())


    // create coordinates object
    let states = {
        "type": "FeatureCollection",
        "features": []
    };

    for (let i = 0; i < pList.length; i++) {
        var polyObj = pList[i][0].map(function (o) {
            return [o.lat, o.lng];
        });
        // Push new object to states
        states.features.push({
            "type": "Feature",
            "properties": { number: pList[i][1] },
            "geometry": {
                "type": "Polygon",
                "coordinates": [polyObj]
            }
        });
    }
    updateNumbers();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const folder = urlParams.get('p')


    axios.post('/update.php', {
        'geo': states,
        'folder': folder
    }).then(function (response) {
    })
        .catch(function (error) {
            console.log(error);
        });
});

var numberLayers = new L.FeatureGroup();
map.addLayer(freeDraw);
map.addLayer(numberLayers);

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const folder = urlParams.get('p')

axios.post('/get.php', {
    folder: folder
}).then(function (response) {
    if (response.data) {
        var geojson = response.data.features;

        geojson.forEach((f) => {
            var temp = []
            f.geometry.coordinates[0].forEach((el) => {
                temp.push(new L.LatLng(el[0], el[1]))
            })
            pList.push([temp, f.properties.number]);
            freeDraw.create(temp);
        })
        updateNumbers();
    }
    allowSend = true;
}).catch(function (error) {
    console.log(error);
    allowSend = true;

});

L.NumberedDivIcon = L.Icon.extend({
    options: {
        number: '',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
        popupAnchor: [0, -8],
        className: 'leaflet-num-icon'
    },

    createIcon: function () {
        var div = document.createElement('div');
        var numdiv = document.createElement('div');
        numdiv.setAttribute("class", "number");
        numdiv.dataset.num = this.options['number'];
        numdiv.innerHTML = this.options['number'].toString() || '';
        div.appendChild(numdiv);
        this._setIconStyles(div, 'icon');
        return div;
    },

    //you could change this to add a shadow like in the normal marker if you really wanted
    createShadow: function () {
        return null;
    }
});

function updateNumbers() {
    numberLayers.clearLayers();
    pList.forEach(el => {
        var layer = L.polygon(el[0]);

        var marker = new L.Marker(layer.getBounds().getCenter(), {
            icon: new L.NumberedDivIcon({ number: el[1] })
        });
        numberLayers.addLayer(marker);
    })

}

restore.onclick = function () {
    freeDraw.create(lastDeleted[0]);
};