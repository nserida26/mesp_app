@extends('layouts.app')

@section('title', __('lang.admin.imports'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">

        {{-- Header --}}
        <div class="mb-6 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900">@lang('lang.admin.import_data')</h1>
            <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.import_help')</p>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                <i class="fas fa-check-circle mt-0.5 text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <i class="fas fa-exclamation-circle mt-0.5 text-red-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->has('import'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <i class="fas fa-exclamation-circle mt-0.5 shrink-0 text-red-400"></i>
                <span>{!! $errors->first('import') !!}</span>
            </div>
        @endif

        {{-- ── EXPORT section ───────────────────────────────────────── --}}
        <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-1 flex items-center gap-2 text-base font-semibold text-gray-900">
                <i class="fas fa-file-excel text-green-600"></i>
                @lang('lang.admin.export_excel')
            </h2>
            <p class="mb-5 text-xs text-gray-500">
                @lang('lang.admin.export_help')
            </p>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ([
                    ['etudiants',    'fas fa-user-graduate', 'lang.admin.students_registrations', 'bg-blue-50 border-blue-200 text-blue-700 hover:bg-blue-100'],
                    ['institutions', 'fas fa-university',    'lang.resources.institutions',       'bg-teal-50 border-teal-200 text-teal-700 hover:bg-teal-100'],
                    ['filieres',     'fas fa-graduation-cap','lang.resources.filieres',           'bg-purple-50 border-purple-200 text-purple-700 hover:bg-purple-100'],
                    ['enseignants',  'fas fa-chalkboard-teacher', 'lang.admin.teachers_assignments', 'bg-orange-50 border-orange-200 text-orange-700 hover:bg-orange-100'],
                ] as [$key, $icon, $label, $classes])
                    <a href="{{ route('admin.exports.download', $key) }}"
                       class="flex flex-col items-center gap-2 rounded-xl border p-4 text-center text-sm font-semibold transition {{ $classes }}">
                        <i class="{{ $icon }} text-2xl opacity-80"></i>
                        {{ __($label) }}
                        <span class="mt-auto text-xs font-normal opacity-70">.xlsx</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ── IMPORT section ───────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

            {{-- Import form --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 flex items-center gap-2 text-base font-semibold text-gray-900">
                        <i class="fas fa-upload text-primary"></i>
                        @lang('lang.actions.import')
                    </h2>

                    <form method="POST" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data" id="import-form">
                        @csrf

                        {{-- Table selector --}}
                        <div class="mb-5">
                            <label for="table" class="mb-1.5 block text-sm font-medium text-gray-700">
                                @lang('lang.admin.target_table')
                            </label>
                            <select id="table" name="table"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-primary focus:ring-primary"
                                onchange="updateColumnGuide(this.value)">
                                @foreach ($tables as $table)
                                    <option value="{{ $table }}" @selected(old('table') === $table)>{{ $table }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- File drop zone --}}
                        <div class="mb-5">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                @lang('lang.admin.file')
                            </label>
                            <label for="file-input"
                                class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 py-8 text-center transition hover:border-primary hover:bg-primary-50"
                                id="drop-zone">
                                <i class="fas fa-file-csv mb-3 text-3xl text-gray-400" id="drop-icon"></i>
                                <p class="text-sm font-medium text-gray-700" id="drop-label">@lang('lang.admin.drop_file')</p>
                                <p class="mt-1 text-xs text-gray-400">@lang('lang.admin.file_hint')</p>
                                <input type="file" id="file-input" name="file" accept=".csv,.xlsx,.xls,.txt"
                                    class="hidden" onchange="onFileSelected(this)" required>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>
                            @lang('lang.actions.import')
                        </button>
                    </form>
                </div>

                {{-- Format info --}}
                <div class="mt-4 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                    <p class="mb-2 font-semibold"><i class="fas fa-info-circle mr-1"></i> @lang('lang.admin.format_rules')</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>@lang('lang.admin.format_rule_header')</li>
                        <li>@lang('lang.admin.format_rule_skip')</li>
                        <li>@lang('lang.admin.format_rule_encoding')</li>
                        <li>@lang('lang.admin.format_rule_size')</li>
                    </ul>
                </div>
            </div>

            {{-- Column guide --}}
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-1 flex items-center gap-2 text-base font-semibold text-gray-900">
                        <i class="fas fa-table text-primary"></i>
                        @lang('lang.admin.columns') — <span id="guide-table-name" class="text-primary">{{ $tables[0] }}</span>
                    </h2>
                    <p class="mb-5 text-xs text-gray-500">@lang('lang.admin.columns_help')</p>

                    @php
                    $columnGuides = [
                        'etudiants' => [
                            ['col'=>'nom',                'type'=>'string',  'req'=>true,  'desc'=>'Nom de famille'],
                            ['col'=>'prenom',             'type'=>'string',  'req'=>true,  'desc'=>'Prénom'],
                            ['col'=>'nni',                'type'=>'string',  'req'=>true,  'desc'=>'Numéro national d\'identité'],
                            ['col'=>'date_naissance',     'type'=>'date',    'req'=>false, 'desc'=>'YYYY-MM-DD ou DD/MM/YYYY'],
                            ['col'=>'lieu_naissance',     'type'=>'string',  'req'=>false, 'desc'=>'Lieu de naissance'],
                            ['col'=>'serie_bac',          'type'=>'string',  'req'=>false, 'desc'=>'Série du baccalauréat'],
                            ['col'=>'annee_bac',          'type'=>'integer', 'req'=>false, 'desc'=>'Année du baccalauréat'],
                            ['col'=>'mention_bac',        'type'=>'string',  'req'=>false, 'desc'=>'Mention obtenue'],
                            ['col'=>'email',              'type'=>'email',   'req'=>false, 'desc'=>'Email de l\'étudiant'],
                            ['col'=>'telephone',          'type'=>'string',  'req'=>false, 'desc'=>'Téléphone'],
                            ['col'=>'code_filiere',       'type'=>'string',  'req'=>true,  'desc'=>'Code unique de la filière (requis)'],
                            ['col'=>'annee_universitaire','type'=>'string',  'req'=>true,  'desc'=>'Ex: 2024-2025'],
                            ['col'=>'semestre',           'type'=>'integer', 'req'=>false, 'desc'=>'Semestre courant (1–12)'],
                            ['col'=>'statut_inscription', 'type'=>'string',  'req'=>false, 'desc'=>'actif / suspendu / inactif'],
                            ['col'=>'date_inscription',   'type'=>'date',    'req'=>false, 'desc'=>'Date d\'inscription (YYYY-MM-DD)'],
                        ],
                        'institutions' => [
                            ['col'=>'nom',                  'type'=>'string', 'req'=>true,  'desc'=>'Nom officiel'],
                            ['col'=>'code_etablissement',   'type'=>'string', 'req'=>false, 'desc'=>'Code interne unique'],
                            ['col'=>'adresse',              'type'=>'string', 'req'=>false, 'desc'=>'Adresse complète'],
                            ['col'=>'ville',                'type'=>'string', 'req'=>false, 'desc'=>'Ville'],
                            ['col'=>'telephone',            'type'=>'string', 'req'=>false, 'desc'=>'Téléphone'],
                            ['col'=>'email',                'type'=>'email',  'req'=>false, 'desc'=>'Email de contact'],
                            ['col'=>'statut',               'type'=>'string', 'req'=>false, 'desc'=>'actif / inactif'],
                            ['col'=>'accred_numero_arrete', 'type'=>'string', 'req'=>false, 'desc'=>'N° arrêté accréditation'],
                            ['col'=>'accred_type',          'type'=>'string', 'req'=>false, 'desc'=>'creation / renouvellement'],
                            ['col'=>'accred_date_arrete',   'type'=>'date',   'req'=>false, 'desc'=>'Date de l\'arrêté'],
                            ['col'=>'accred_date_debut',    'type'=>'date',   'req'=>false, 'desc'=>'Date début validité'],
                            ['col'=>'accred_date_fin',      'type'=>'date',   'req'=>false, 'desc'=>'Date fin validité'],
                            ['col'=>'accred_statut',        'type'=>'string', 'req'=>false, 'desc'=>'active / expiree / suspendue'],
                        ],
                        'filieres' => [
                            ['col'=>'nom_filiere',                  'type'=>'string',  'req'=>true,  'desc'=>'Intitulé de la filière'],
                            ['col'=>'code_filiere',                 'type'=>'string',  'req'=>false, 'desc'=>'Code unique filière'],
                            ['col'=>'niveau',                       'type'=>'string',  'req'=>false, 'desc'=>'licence / master / doctorat'],
                            ['col'=>'duree_semestres',              'type'=>'integer', 'req'=>false, 'desc'=>'Nombre de semestres'],
                            ['col'=>'capacite',                     'type'=>'integer', 'req'=>false, 'desc'=>'Capacité d\'accueil'],
                            ['col'=>'numero_arrete_autorisation',   'type'=>'string',  'req'=>false, 'desc'=>'N° arrêté autorisation'],
                            ['col'=>'date_arrete',                  'type'=>'date',    'req'=>false, 'desc'=>'Date de l\'arrêté'],
                            ['col'=>'statut',                       'type'=>'string',  'req'=>false, 'desc'=>'active / inactive'],
                            ['col'=>'code_etablissement',           'type'=>'string',  'req'=>true,  'desc'=>'Code de l\'institution parente'],
                            ['col'=>'institution',                  'type'=>'string',  'req'=>false, 'desc'=>'Nom institution (si pas de code)'],
                            ['col'=>'accred_numero_arrete',         'type'=>'string',  'req'=>false, 'desc'=>'N° arrêté accréditation filière'],
                            ['col'=>'accred_type',                  'type'=>'string',  'req'=>false, 'desc'=>'creation / renouvellement'],
                            ['col'=>'accred_date_debut',            'type'=>'date',    'req'=>false, 'desc'=>'Date début validité'],
                            ['col'=>'accred_date_fin',              'type'=>'date',    'req'=>false, 'desc'=>'Date fin validité'],
                            ['col'=>'accred_statut',                'type'=>'string',  'req'=>false, 'desc'=>'active / expiree / suspendue'],
                        ],
                        'enseignants' => [
                            ['col'=>'nom',                  'type'=>'string', 'req'=>true,  'desc'=>'Nom de famille'],
                            ['col'=>'prenom',               'type'=>'string', 'req'=>true,  'desc'=>'Prénom'],
                            ['col'=>'nni',                  'type'=>'string', 'req'=>true,  'desc'=>'Numéro national'],
                            ['col'=>'numero_accreditation', 'type'=>'string', 'req'=>false, 'desc'=>'N° accréditation enseignant'],
                            ['col'=>'grade',                'type'=>'string', 'req'=>false, 'desc'=>'assistant / maitre_assistant / maitre_conference / professeur'],
                            ['col'=>'specialite',           'type'=>'string', 'req'=>false, 'desc'=>'Domaine de spécialité'],
                            ['col'=>'email',                'type'=>'email',  'req'=>false, 'desc'=>'Email professionnel'],
                            ['col'=>'telephone',            'type'=>'string', 'req'=>false, 'desc'=>'Téléphone'],
                            ['col'=>'statut',               'type'=>'string', 'req'=>false, 'desc'=>'actif / inactif'],
                            ['col'=>'code_etablissement',   'type'=>'string', 'req'=>true,  'desc'=>'Code de l\'institution d\'affectation'],
                            ['col'=>'code_filiere',         'type'=>'string', 'req'=>true,  'desc'=>'Code de la filière d\'affectation'],
                            ['col'=>'annee_univ',           'type'=>'integer','req'=>false, 'desc'=>'Année universitaire (ex: 2024)'],
                            ['col'=>'volume_horaire',       'type'=>'integer','req'=>false, 'desc'=>'Volume horaire'],
                            ['col'=>'type_contrat',         'type'=>'string', 'req'=>false, 'desc'=>'permanent / vacataire'],
                        ],
                        'accreditations' => [
                            ['col'=>'institution_uuid','type'=>'uuid',   'req'=>true,  'desc'=>'UUID de l\'institution'],
                            ['col'=>'numero_arrete',  'type'=>'string', 'req'=>true,  'desc'=>'N° de l\'arrêté'],
                            ['col'=>'type',           'type'=>'string', 'req'=>true,  'desc'=>'creation / renouvellement'],
                            ['col'=>'date_debut',     'type'=>'date',   'req'=>true,  'desc'=>'Date début (YYYY-MM-DD)'],
                            ['col'=>'date_fin',       'type'=>'date',   'req'=>true,  'desc'=>'Date fin (YYYY-MM-DD)'],
                            ['col'=>'statut',         'type'=>'string', 'req'=>false, 'desc'=>'active / expiree / suspendue'],
                        ],
                        'calendriers_academiques' => [
                            ['col'=>'annee_universitaire','type'=>'string',  'req'=>true,  'desc'=>'Ex: 2024-2025'],
                            ['col'=>'semestre',           'type'=>'integer', 'req'=>true,  'desc'=>'Numéro de semestre'],
                            ['col'=>'date_debut',         'type'=>'date',    'req'=>true,  'desc'=>'Début du semestre (YYYY-MM-DD)'],
                            ['col'=>'date_fin',           'type'=>'date',    'req'=>true,  'desc'=>'Fin du semestre (YYYY-MM-DD)'],
                            ['col'=>'institution_uuid',   'type'=>'uuid',    'req'=>false, 'desc'=>'UUID institution (null = global)'],
                        ],
                    ];
                    @endphp

                    <script>const columnGuides = @json($columnGuides);</script>

                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-100 text-xs" id="guide-table">
                            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-2.5">@lang('lang.admin.column')</th>
                                    <th class="px-4 py-2.5">@lang('lang.admin.type')</th>
                                    <th class="px-4 py-2.5 text-center">@lang('lang.admin.required')</th>
                                    <th class="px-4 py-2.5">@lang('lang.admin.description')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white" id="guide-body"></tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-3 text-xs text-gray-500">
                        <span><span class="rounded bg-blue-100 px-1.5 py-0.5 font-mono text-blue-700">string</span> @lang('lang.admin.text_type')</span>
                        <span><span class="rounded bg-purple-100 px-1.5 py-0.5 font-mono text-purple-700">integer</span> @lang('lang.admin.integer_type')</span>
                        <span><span class="rounded bg-orange-100 px-1.5 py-0.5 font-mono text-orange-700">date</span> @lang('lang.admin.date_type')</span>
                        <span><span class="rounded bg-teal-100 px-1.5 py-0.5 font-mono text-teal-700">email</span> @lang('lang.admin.email_type')</span>
                        <span><span class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-gray-700">uuid</span> UUID</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
const typeColors = {
    string:'bg-blue-100 text-blue-700', integer:'bg-purple-100 text-purple-700',
    date:'bg-orange-100 text-orange-700', email:'bg-teal-100 text-teal-700', uuid:'bg-gray-100 text-gray-700',
};

function updateColumnGuide(table) {
    const cols  = columnGuides[table] || [];
    document.getElementById('guide-table-name').textContent = table;
    document.getElementById('guide-body').innerHTML = cols.map(c => `
        <tr>
            <td class="px-4 py-2.5 font-mono font-semibold text-gray-800">${c.col}</td>
            <td class="px-4 py-2.5"><span class="rounded px-1.5 py-0.5 font-mono ${typeColors[c.type]||'bg-gray-100 text-gray-700'}">${c.type}</span></td>
            <td class="px-4 py-2.5 text-center">${c.req ? '<span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">✓</span>' : '<span class="text-gray-300">—</span>'}</td>
            <td class="px-4 py-2.5 text-gray-600">${c.desc}</td>
        </tr>`).join('');
}

function onFileSelected(input) {
    if (input.files?.[0]) {
        document.getElementById('drop-label').textContent = input.files[0].name;
        document.getElementById('drop-icon').className = 'fas fa-file-alt mb-3 text-3xl text-primary';
    }
}

const zone = document.getElementById('drop-zone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-primary','bg-primary-50'); });
zone.addEventListener('dragleave', () => zone.classList.remove('border-primary','bg-primary-50'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('border-primary','bg-primary-50');
    const fi = document.getElementById('file-input');
    fi.files = e.dataTransfer.files;
    onFileSelected(fi);
});

updateColumnGuide(document.getElementById('table').value);
</script>
@endsection
