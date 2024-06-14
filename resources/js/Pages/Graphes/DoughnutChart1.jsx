import React, { Component } from 'react';
import CanvasJSReact from '@canvasjs/react-charts';
var CanvasJSChart = CanvasJSReact.CanvasJSChart;

class DoughnutChart1 extends Component {
	render() {

		// Utiliser les données passées en tant que prop
		const { data } = this.props;

		// Transformer les données en dataPoints pour le graphique
		const dataPoints = [
			{
				name: 'Autre référence', y: 100 - parseFloat(data.pourcentage_Lait_infantile), color: 'transparent'
			},
	
			{
				name: 'Cereale',
				y: parseFloat(data.pourcentage_Lait_infantile), color: 'blue' // Assurez-vous de convertir en nombre
			},
		];

		const options = {
			animationEnabled: true,
			title: {
				text: "Lait Infantile "
			},
			
			data: [
				{
				  type: 'doughnut',
				  showInLegend: false,
				  yValueFormatString: '#,###\'%\'', // Display percentage for Cereale
				  indexLabelFormatter: function(e) {
					return e.dataPoint.name === 'Autre référence'
					  ? "" // Hide label for Empty
					  : `${e.dataPoint.name}: ${e.dataPoint.y}%\n`; // Display name and percentage with a newline
				  },
				  dataPoints: dataPoints,
				  indexLabel: "{y}",
				},
			  ],
		}

		return (
			<div>
				<CanvasJSChart options={options}
				/* onRef={ref => this.chart = ref} */
				/>
				{/*You can get reference to the chart instance as shown above using onRef. This allows you to access all chart properties and methods*/}
			</div>
		);
	}
}

export default DoughnutChart1;