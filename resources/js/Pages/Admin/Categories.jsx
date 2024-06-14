import { Head } from '@inertiajs/react';

export default function Categories({ categories }) {

    return (

        <div>
            <Head title="categories" />




            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                        <pre>{JSON.stringify(categories, undefined, 1)}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
