import { Head } from '@inertiajs/react';

export default function Utilisateur_has_produits({ utilisateur_has_produits }) {

    return (

        <div>
            <Head title="Utilisateur_has_produits" />




            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                        <pre>{JSON.stringify(utilisateur_has_produits, undefined, 1)}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
