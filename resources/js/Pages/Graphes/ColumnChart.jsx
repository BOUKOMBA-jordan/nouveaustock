import React, { Component } from "react";
import CanvasJSReact from '@canvasjs/react-charts';
var CanvasJSChart = CanvasJSReact.CanvasJSChart;

class ChartLabel extends Component {
  render() {
    // Utiliser les données passées en tant que prop
    const { data } = this.props;

    // Trier les données par ordre décroissant du total des ventes
    const sortedData = data.sort((a, b) => b.total_quantite - a.total_quantite);

    // Transformer les données triées en dataPoints pour le graphique
    const dataPoints = data.map(item => ({
      label: item.user_name,
      y: parseFloat(item.total_quantite),
      indexLabel: "{y}"
    }));

    const options = {
      animationEnabled: true,
      exportEnabled: true,
      theme: "light2", //"light1", "dark1", "dark2"
      title:{
        text: "Classement des Commerciaux"
      },
      data: [{
        type: "column", //change type to bar, line, area, pie, etc
        dataPoints: dataPoints,
      }]
    }
    
    return (
      <div className="ChartWithIndexLabel">
        <CanvasJSChart options = {options} />
      </div>
    );
  }
}

export default ChartLabel;
