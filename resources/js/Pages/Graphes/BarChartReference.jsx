import React, { Component } from 'react';
import CanvasJSReact from '@canvasjs/react-charts';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

var CanvasJSChart = CanvasJSReact.CanvasJSChart;

class BarChartReference extends Component {
	render() {
		// Utiliser les données passées en tant que prop
		const { data } = this.props;

		// Trier les données par ordre décroissant du total des ventes
		const sortedData = data.sort((a, b) => b.total_quantite - a.total_quantite);

		// Transformer les données triées en dataPoints pour le graphique
		const dataPoints = sortedData.map(item => ({
			label: item.reference_produit,
			y: parseFloat(item.total_quantite)
		}));

		const options = {
			exportEnabled: true,
			animationEnabled: true,
			theme: "light2",
			title: {
				text: "Top Reférence"
			},
			axisX: {
				title: "Reférences",
				reversed: true,
			},
			axisY: {
				title: "Quantités",
				labelFormatter: function () { return "" }, // Masquer les valeurs sur l'axe Y
				gridThickness: 0 // Enlever les lignes de repère sur l'axe Y
			},
			data: [{
				type: "bar",
				indexLabel: "{y}",
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

export default BarChartReference;
