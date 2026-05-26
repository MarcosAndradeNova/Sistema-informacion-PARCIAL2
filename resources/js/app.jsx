import React from 'react';
import { createRoot } from 'react-dom/client';

function App() {
    return (
        <main className="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-6">
            <section className="w-full max-w-xl rounded-2xl border border-slate-700 bg-slate-900/70 p-8 shadow-xl">
                <h1 className="text-3xl font-semibold tracking-tight">Laravel + React</h1>
                <p className="mt-3 text-slate-300">
                    React se instaló correctamente y está corriendo con Vite en este proyecto.
                </p>
                <p className="mt-6 text-sm text-slate-400">
                    Edita <code>resources/js/app.jsx</code> para empezar a construir tu UI.
                </p>
            </section>
        </main>
    );
}

const rootElement = document.getElementById('app');

if (rootElement) {
    createRoot(rootElement).render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    );
}
