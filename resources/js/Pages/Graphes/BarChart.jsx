import React, { Component } from 'react';
import CanvasJSReact from '@canvasjs/react-charts';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

var CanvasJSChart = CanvasJSReact.CanvasJSChart;

class BarChart extends Component {
	render() {
		// Utiliser les données passées en tant que prop
		const { data } = this.props;

		// Trier les données par ordre décroissant du total des ventes
		const sortedData = data.sort((a, b) => b.total_vente - a.total_vente);

		// Transformer les données triées en dataPoints pour le graphique
		const dataPoints = sortedData.map(item => ({
			label: item.user_name,
			y: parseFloat(item.total_vente)
		}));

		const options = {
			exportEnabled: true,
			animationEnabled: true,
			theme: "light2",
			title: {
				text: "Total des ventes par commercial"
			},
			axisX: {
				title: "Commerciaux",
				reversed: true,
			},
			axisY: {
				title: "Total des ventes",
				labelFormatter: this.addSymbols
			},
			data: [{
				type: "bar",
				indexLabel: "{y} FCFA",
				dataPoints: dataPoints,
				
			}]
		}

		return (
			<div>
				<CanvasJSChart options={options} />
			</div>
		);
	}
}

export default BarChart;
