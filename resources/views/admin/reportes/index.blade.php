<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-indigo-600 pl-3">
            {{ __('Generar Reportes Interactivos') }}
        </h2>
    </x-slot>

    <!-- Importar Alpine.js (para la reactividad de la tabla) y html2pdf (para PDF) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        /* Ocultar elementos en la impresión PDF nativa si fuera necesaria */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
        }
        
        /* Animación para el micrófono */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .listening {
            animation: pulse-ring 2s infinite;
            background-color: #ef4444 !important;
            color: white !important;
        }
    </style>

    <div class="py-12 bg-gray-100 min-h-screen" x-data="reportesApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Controles Superiores -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-6 no-print">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    
                    <!-- Buscador e IA -->
                    <div class="w-full md:w-1/2 relative">
                        <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Filtro de Reporte (Texto o Voz)</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" x-model="searchQuery" placeholder="Ej: estudiantes aprobados, sin grupo, G1..." class="flex-1 focus:ring-indigo-600 focus:border-indigo-600 block w-full min-w-0 rounded-none rounded-l-md sm:text-sm border-gray-300 px-4 py-3 bg-gray-50 text-gray-900">
                            
                            <!-- Botón de Reconocimiento de Voz -->
                            <button @click="startListening()" :class="isListening ? 'listening' : 'bg-white text-gray-400 hover:text-indigo-600 hover:bg-gray-50'" class="inline-flex items-center px-4 border border-l-0 border-gray-300 rounded-r-md transition-colors" title="Hablar comando">
                                <svg x-show="!isListening" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                <svg x-show="isListening" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8h-2a5 5 0 01-10 0H3a7.001 7.001 0 006 6.93V17H6v2h8v-2h-3v-2.07z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-indigo-600 italic font-medium" x-show="isListening">Escuchando... Di algo como "estudiantes sin grupo"</p>
                        <p class="mt-2 text-xs text-gray-500" x-show="!isListening">Puedes escribir directamente o usar el micrófono para filtrar inteligentemente.</p>
                    </div>

                    <!-- Botones de Exportación -->
                    <div class="flex gap-3 w-full md:w-auto">
                        <button @click="exportCSV()" class="flex-1 md:flex-none flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar CSV
                        </button>
                        <button @click="exportPDF()" class="flex-1 md:flex-none flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:outline-none transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar PDF
                        </button>
                    </div>

                </div>
            </div>

            <!-- Tabla de Reporte (Contenedor PDF) -->
            <div id="reporte-contenedor" class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wide" x-text="tituloReporte">Listado General de Estudiantes</h3>
                    <span class="text-xs font-bold text-slate-800 bg-white px-3 py-1 rounded-full shadow-sm">
                        <span x-text="filteredEstudiantes.length"></span> Registros
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">CI</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grupo</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado Docum.</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Promedio Gral.</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado Notas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="est in filteredEstudiantes" :key="est.ci">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono" x-text="est.ci"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900" x-text="est.nombre"></div>
                                        <div class="text-xs text-gray-500" x-text="est.email"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold" 
                                              :class="est.grupo === 'SIN GRUPO' ? 'bg-red-100 text-red-800' : 'bg-indigo-100 text-indigo-800'"
                                              x-text="est.grupo">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900" x-text="est.estado_docum"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-mono font-bold text-gray-900" x-text="est.promedio"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full border"
                                              :class="{
                                                'bg-green-50 border-green-200 text-green-700': est.estado === 'APROBADO',
                                                'bg-red-50 border-red-200 text-red-700': est.estado === 'REPROBADO',
                                                'bg-gray-100 border-gray-200 text-gray-600': est.estado === 'SIN NOTAS'
                                              }"
                                              x-text="est.estado">
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredEstudiantes.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    No se encontraron estudiantes que coincidan con la búsqueda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reportesApp', () => ({
                estudiantes: @json($estudiantesData),
                searchQuery: '',
                isListening: false,
                recognition: null,

                init() {
                    // Configurar Speech Recognition
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (SpeechRecognition) {
                        this.recognition = new SpeechRecognition();
                        this.recognition.continuous = false;
                        this.recognition.lang = 'es-ES';
                        this.recognition.interimResults = false;

                        this.recognition.onstart = () => {
                            this.isListening = true;
                        };

                        this.recognition.onresult = (event) => {
                            const transcript = event.results[0][0].transcript;
                            this.processVoiceCommand(transcript.toLowerCase());
                        };

                        this.recognition.onerror = (event) => {
                            console.error("Error en reconocimiento de voz", event.error);
                            this.isListening = false;
                            alert("No se pudo reconocer la voz. Verifique su micrófono.");
                        };

                        this.recognition.onend = () => {
                            this.isListening = false;
                        };
                    } else {
                        console.warn("Speech Recognition API no soportada en este navegador.");
                    }
                },

                startListening() {
                    if (!this.recognition) {
                        alert("El reconocimiento de voz no está soportado en tu navegador actual (Usa Chrome o Edge).");
                        return;
                    }
                    if (this.isListening) {
                        this.recognition.stop();
                    } else {
                        this.searchQuery = '';
                        this.recognition.start();
                    }
                },

                processVoiceCommand(command) {
                    console.log("Comando recibido:", command);
                    
                    // IA Mapeo Básico de Intenciones
                    if (command.includes("sin grupo") || command.includes("no tienen grupo")) {
                        this.searchQuery = "SIN GRUPO";
                    } else if (command.includes("aprobados")) {
                        this.searchQuery = "APROBADO";
                    } else if (command.includes("reprobados") || command.includes("aplazados")) {
                        this.searchQuery = "REPROBADO";
                    } else if (command.includes("inscritos")) {
                        this.searchQuery = "INSCRITO";
                    } else if (command.includes("grupo")) {
                        // Extraer numero, ej "grupo uno"
                        const numMap = {"uno": 1, "dos": 2, "tres": 3, "cuatro": 4};
                        let found = command.replace(/uno|dos|tres|cuatro/g, m => numMap[m]);
                        let match = found.match(/\d+/);
                        if (match) {
                            this.searchQuery = "Grupo " + match[0];
                        } else {
                            this.searchQuery = command;
                        }
                    } else {
                        this.searchQuery = command;
                    }
                },

                get filteredEstudiantes() {
                    if (this.searchQuery === '') return this.estudiantes;
                    
                    const lowerQuery = this.searchQuery.toLowerCase();
                    return this.estudiantes.filter(est => {
                        return est.nombre.toLowerCase().includes(lowerQuery) ||
                               est.ci.toLowerCase().includes(lowerQuery) ||
                               est.grupo.toLowerCase().includes(lowerQuery) ||
                               est.estado.toLowerCase().includes(lowerQuery) ||
                               est.estado_docum.toLowerCase().includes(lowerQuery);
                    });
                },

                get tituloReporte() {
                    if (this.searchQuery === '') return "Listado General de Estudiantes";
                    return `Reporte Filtrado: "${this.searchQuery.toUpperCase()}"`;
                },

                exportPDF() {
                    const element = document.getElementById('reporte-contenedor');
                    const opt = {
                        margin:       0.5,
                        filename:     'Reporte_Administrativo.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2 },
                        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
                    };
                    
                    // html2pdf genera un PDF perfectamente ajustado
                    html2pdf().set(opt).from(element).save();
                },

                exportCSV() {
                    let csvContent = "data:text/csv;charset=utf-8,";
                    // Encabezados
                    csvContent += "CI,Nombre,Email,Grupo,Estado_Documentos,Promedio,Estado_Notas\n";
                    
                    this.filteredEstudiantes.forEach(est => {
                        // Limpiar comas en el nombre
                        let nom = est.nombre.replace(/,/g, '');
                        let row = `${est.ci},${nom},${est.email},${est.grupo},${est.estado_docum},${est.promedio},${est.estado}`;
                        csvContent += row + "\n";
                    });

                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "Reporte_Estudiantes.csv");
                    document.body.appendChild(link); // Required for FF
                    link.click();
                    document.body.removeChild(link);
                }
            }));
        });
    </script>
</x-app-layout>
