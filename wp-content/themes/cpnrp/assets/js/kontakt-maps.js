document.querySelectorAll('.kontakt-office-map[data-lat]').forEach(function (el) {
	var lat   = parseFloat(el.dataset.lat);
	var lon   = parseFloat(el.dataset.lon);
	var label = el.dataset.label || '';

	var markerHtml = '<div class="kontakt-map-pin"><svg width="30" height="38" viewBox="0 0 30 38" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 0C6.716 0 0 6.716 0 15c0 10.059 13.5 22.177 13.987 22.621a1.371 1.371 0 002.026 0C16.5 37.177 30 25.059 30 15 30 6.716 23.284 0 15 0z" fill="#1A6080"/><circle cx="15" cy="15" r="6.5" fill="white"/></svg></div>';

	var customIcon = L.divIcon({
		html:        markerHtml,
		className:   '',
		iconSize:    [30, 38],
		iconAnchor:  [15, 38],
		popupAnchor: [0, -40],
	});

	var map = L.map(el, {
		center:             [lat, lon],
		zoom:               16,
		scrollWheelZoom:    false,
		dragging:           false,
		tap:                false,
		zoomControl:        true,
		attributionControl: true,
	});

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>',
		maxZoom: 19,
	}).addTo(map);

	L.marker([lat, lon], { icon: customIcon })
		.addTo(map)
		.bindPopup('<strong>' + label + '</strong>');

	el.addEventListener('click',      function () { map.dragging.enable(); });
	el.addEventListener('mouseleave', function () { map.dragging.disable(); });
});
