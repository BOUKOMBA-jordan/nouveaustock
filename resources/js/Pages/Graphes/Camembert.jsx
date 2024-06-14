import React, { Component } from 'react';
import CanvasJSReact from '@canvasjs/react-charts';
var CanvasJSChart = CanvasJSReact.CanvasJSChart;

class Camembert extends Component {
	render() {

		// Utiliser les données passées en tant que prop
		const { data } = this.props;

		// Transformer les données en dataPoints pour le graphique
		const Laits = data.map(item => ({
			label: "Laits",
			y: parseInt(item.Laits),
		}));

		// Transformer les données en dataPoints pour le graphique
		const Cereale = data.map(item => ({
			label: "Céréale",
			y: parseInt(item.Cereale),
		}));

		const dataPoints = Laits.concat(Cereale);

		const options = {
			theme: "dark2",
			animationEnabled: true,
			exportFileName: "New Year Resolutions",
			exportEnabled: true,
			title: {
				text: ""
			},
			data: [{
				type: "pie",
				showInLegend: true,
				legendText: "{label}",
				toolTipContent: "{label}: <strong>{y}%</strong>",
				indexLabel: "{y}%",
				indexLabelPlacement: "inside",
				dataPoints: dataPoints
			}]
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

export default Camembert;