"use strict";

var temperatureChartInit = function () {
	let elements = document.getElementsByClassName('temperatureChart');

	for (let i = 0; i < elements.length; i++) {
		let element = elements[ i ];

		new TemperatureChart(element);
	}
}

document.addEventListener("DOMContentLoaded", function (event) {
	temperatureChartInit();
});