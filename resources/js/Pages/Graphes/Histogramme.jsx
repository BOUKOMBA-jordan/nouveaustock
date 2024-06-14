import React, { Component } from 'react';
import CanvasJSReact from '@canvasjs/react-charts';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '../../../css/histogramme.css'; // Assurez-vous d'inclure le fichier CSS

var CanvasJSChart = CanvasJSReact.CanvasJSChart;

class Histogramme extends Component {
	render() {
		// Utiliser les données passées en tant que prop
		const { data } = this.props;

		// Transformer les données en dataPoints pour le graphique
		const dataPoints = data.map(item => ({
			label: item.date,
			y: parseInt(item.total_quantite, 10)
		}));

		const options = {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light2",
			title: {
				text: "Vente en carton"
			},
			animationEnabled: true,
			data: [
				{
					type: "column",
					dataPoints: dataPoints,
					indexLabel: "{y}",
				}
			]
		};

		return (
			<div className="ChartWithIndexLabel">
				<CanvasJSChart options={options} />
			</div>
		);
	}
}

export default Histogramme;
