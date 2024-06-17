import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import Histogramme from './Graphes/Histogramme.jsx';
import Histogramme1 from './Graphes/Histogramme1.jsx';
import BarChart from './Graphes/BarChart.jsx';
import BarChartReference from './Graphes/BarChartReference.jsx';
import LineChart from './Graphes/LineChart.jsx';
import Camembert from './Graphes/Camembert.jsx';
import ChartLabel from './Graphes/ColumnChart.jsx';
import DoughnutChart from './Graphes/DoughnutChart.jsx';
import DoughnutChart1 from './Graphes/DoughnutChart1.jsx';
import DoughnutChart2 from './Graphes/DoughnutChart2.jsx';
import React, { useState, useEffect } from 'react';

export default function Dashboard({
    auth,
    utilisateur_has_produits,
    total_ventes_par_utilisateur,
    ventes_totales_par_jour,
    results,
    ventes_par_mois_par_utilisateur,
    poucentageLaitCereale,
    pourcentageCerealesVendues,
    pourcentageLaitInfantile,
    pourcentageLaitDeCroissance,
    quantitesVenduesParMois,
    quantitesVenduesMoisEnCours,
    sommeTotale,
    anneesMois,
    year,
    month
}) {
    // Utiliser le hook usePage pour accéder aux propriétés passées en paramètre dans l'URL
    //const { props } = usePage();
    //const year = props.year || 2024; // Utiliser 2024 comme valeur par défaut si year n'est pas fourni
    //const month = props.month || 5;  // Utiliser 5 comme valeur par défaut si month n'est pas fourni

    let quantiteTotale;
    if (quantitesVenduesMoisEnCours.length > 0) {
        quantiteTotale = parseInt(quantitesVenduesMoisEnCours[0].quantite_totale, 10);
    } else {
        quantiteTotale = 0;
    }

    const [selectedRoute, setSelectedRoute] = useState(route('dashboard', { year, month }));

    useEffect(() => {
        setSelectedRoute(route('dashboard', { year, month }));
    }, [year, month]);

    const handleSelectionChange = (event) => {
        setSelectedRoute(event.target.value);
        if (event.target.value) {
            window.location.href = event.target.value;
        }
    };

    const getMonthName = (monthNumber) => {
        const monthNames = [
            "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
        ];
        return monthNames[monthNumber - 1];
    };

    const formattedAmount = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XAF' }).format(sommeTotale);

    return (
        <AuthenticatedLayout
            user={auth.user}
            year={year}
            month={month}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="row p-6 text-gray-900">
                            <div className="col-lg-4">
                                <select className="form-select" value={selectedRoute} onChange={handleSelectionChange}>
                                    {anneesMois.map((item, index) => (
                                        <option
                                            key={index}
                                            value={route('dashboard', { year: item.annee, month: item.mois })}>
                                            {`${getMonthName(item.mois)} ${item.annee}`}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div className="col-lg-4">
                                <p className="mb-0 chiffreDaffaire">Cartons : {quantiteTotale}</p>
                            </div>
                            <div className="col-lg-4">
                                <p className="mb-0 chiffreDaffaire">Chiffre d'Affaire : {formattedAmount}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="py-6">
                <div className="mx-auto sm:px-6 lg:px-8">
                    <div className="overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                        <div className="row">
                            <LineChart data={ventes_totales_par_jour} />
                        </div>
                        <div className="row">
                            <div className="col-md-6 col-lg-6 mt-5">
                                <Histogramme data={utilisateur_has_produits} />
                            </div>
                            <div className="col-md-6 col-lg-6 mt-5">
                                <Histogramme1 data={quantitesVenduesParMois} />
                            </div>

                            
                        </div>

                        <div className="row">
                            <div className="col-md-6 mt-5">
                                <BarChartReference data={results} />
                            </div>
                            <div className="col-md-3 mt-5">
                                <BarChart data={total_ventes_par_utilisateur} />
                            </div>
                            <div className="col-md-3 mt-5">
                                <ChartLabel data={ventes_par_mois_par_utilisateur} />
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-md-3 col-lg-3 mt-5">
                                <Camembert data={poucentageLaitCereale} />
                            </div>
                            <div className="col-md-3 col-lg-3 mt-5">
                                <DoughnutChart data={pourcentageCerealesVendues} />
                            </div>
                            <div className="col-md-3 col-lg-3 mt-5">
                                <DoughnutChart1 data={pourcentageLaitInfantile} />
                            </div>
                            <div className="col-md-3 col-lg-3 mt-5">
                                <DoughnutChart2 data={pourcentageLaitDeCroissance} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
