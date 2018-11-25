"use strict";

class TemperatureChart {

	constructor (element) {
		this.element = element;
		this.init();
	}

	init () {
		let data = JSON.parse(this.element.getAttribute('data-chart'));

		if (typeof data[ 0 ] === 'undefined') {
			return false;
		}

		this.jsonData = data;

		new Chart(this.element, {
			type:    'line',
			data:    {
				labels:   this.getLabels(),
				datasets: this.getDatasets()
			},
			options: {
				scales: {
					yAxes: [ {
						ticks: {
							suggestedMin: -5,
							suggestedMax: 30,
							stepSize:     2
						}
					} ]
				}
			}
		});
	}

	getLabels () {
		return this.jsonData[ 0 ].dates;
	}

	getDatasets () {
		let res = [];

		for (let index in this.jsonData) {
			let data = this.jsonData[ index ];

			let dataset = {
				label:                data.name,
				data:                 data.values,
				pointRadius:          1,
				backgroundColor:      'transparent',
				borderColor:          '#007bff',
				borderWidth:          2,
				pointBackgroundColor: '#007bff'
			};

			res.push(dataset);
		}

		return res;
	}
}