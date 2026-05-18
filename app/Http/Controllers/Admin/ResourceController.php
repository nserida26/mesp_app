<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\AffectationEnseignant;
use App\Models\CalendrierAcademique;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    private array $resources = [
        'institutions' => [
            'model' => Institution::class,
            'label' => 'Institutions',
            'singular' => 'institution',
            'title' => ['nom', 'code_etablissement'],
            'search' => ['nom', 'code_etablissement', 'ville', 'email'],
            'fields' => [
                'nom' => ['type' => 'text', 'required' => true],
                'code_etablissement' => ['type' => 'text', 'required' => true, 'unique' => true],
                'adresse' => ['type' => 'textarea'],
                'ville' => ['type' => 'text', 'required' => true],
                'telephone' => ['type' => 'text'],
                'email' => ['type' => 'email', 'unique' => true],
                'statut' => ['type' => 'select', 'required' => true, 'options' => ['actif' => 'Actif', 'suspendu' => 'Suspendu', 'ferme' => 'Ferme']],
                'logo_path' => ['type' => 'text'],
            ],
        ],
        'filieres' => [
            'model' => Filiere::class,
            'label' => 'Filieres',
            'singular' => 'filiere',
            'title' => ['nom', 'code_filiere'],
            'search' => ['nom', 'code_filiere', 'niveau'],
            'with' => ['institution'],
            'fields' => [
                'institution_id' => ['type' => 'relation', 'required' => true, 'model' => Institution::class, 'label_field' => 'nom'],
                'code_filiere' => ['type' => 'text', 'required' => true, 'unique' => true],
                'nom' => ['type' => 'text', 'required' => true],
                'niveau' => ['type' => 'select', 'required' => true, 'options' => ['licence' => 'Licence', 'master' => 'Master', 'doctorat' => 'Doctorat']],
                'duree_semestres' => ['type' => 'number', 'required' => true, 'min' => 1],
                'numero_arrete_autorisation' => ['type' => 'text'],
                'date_arrete_autorisation' => ['type' => 'date'],
                'capacite_accueil' => ['type' => 'number', 'required' => true, 'min' => 0],
                'statut' => ['type' => 'select', 'required' => true, 'options' => ['active' => 'Active', 'inactive' => 'Inactive']],
            ],
        ],
        'etudiants' => [
            'model' => Etudiant::class,
            'label' => 'Etudiants',
            'singular' => 'etudiant',
            'title' => ['nom', 'prenom'],
            'search' => ['nom', 'prenom', 'email'],
            'fields' => [
                'nom' => ['type' => 'text', 'required' => true],
                'prenom' => ['type' => 'text', 'required' => true],
                'date_naissance' => ['type' => 'date'],
                'lieu_naissance' => ['type' => 'text'],
                'numero_national' => ['type' => 'text'],
                'hash_numero_bac' => ['type' => 'text', 'required' => true, 'unique' => true, 'label' => 'Numero bac', 'write_only' => true],
                'serie_bac' => ['type' => 'text'],
                'annee_bac' => ['type' => 'number', 'min' => 1900],
                'mention_bac' => ['type' => 'text'],
                'email' => ['type' => 'email'],
                'telephone' => ['type' => 'text'],
            ],
        ],
        'inscriptions' => [
            'model' => Inscription::class,
            'label' => 'Inscriptions',
            'singular' => 'inscription',
            'title' => ['numero_inscription', 'statut'],
            'search' => ['numero_inscription', 'statut', 'annee_universitaire'],
            'with' => ['etudiant', 'filiere'],
            'fields' => [
                'etudiant_id' => ['type' => 'relation', 'required' => true, 'model' => Etudiant::class, 'label_field' => 'nom_complet'],
                'filiere_id' => ['type' => 'relation', 'required' => true, 'model' => Filiere::class, 'label_field' => 'nom'],
                'numero_inscription' => ['type' => 'text', 'unique' => true, 'readonly_on_create' => true],
                'date_inscription' => ['type' => 'date', 'required' => true],
                'statut' => ['type' => 'select', 'required' => true, 'options' => ['actif' => 'Actif', 'suspendu' => 'Suspendu', 'termine' => 'Termine']],
                'semestre_courant' => ['type' => 'number', 'required' => true, 'min' => 1],
                'annee_universitaire' => ['type' => 'number', 'required' => true, 'min' => 1900],
                'moyenne_generale' => ['type' => 'number', 'step' => '0.01', 'min' => 0],
            ],
        ],
        'enseignants' => [
            'model' => Enseignant::class,
            'label' => 'Enseignants',
            'singular' => 'enseignant',
            'title' => ['nom', 'prenom'],
            'search' => ['nom', 'prenom', 'numero_national', 'numero_accreditation', 'email', 'specialite'],
            'fields' => [
                'nom' => ['type' => 'text', 'required' => true],
                'prenom' => ['type' => 'text', 'required' => true],
                'numero_national' => ['type' => 'text'],
                'numero_accreditation' => ['type' => 'text', 'required' => true, 'unique' => true],
                'grade' => ['type' => 'select', 'required' => true, 'options' => ['assistant' => 'Assistant', 'maitre_assistant' => 'Maitre assistant', 'maitre_conference' => 'Maitre de conferences', 'professeur' => 'Professeur']],
                'specialite' => ['type' => 'text'],
                'email' => ['type' => 'email', 'unique' => true],
                'telephone' => ['type' => 'text'],
                'statut' => ['type' => 'select', 'required' => true, 'options' => ['actif' => 'Actif', 'inactif' => 'Inactif']],
            ],
        ],
        'accreditations' => [
            'model' => Accreditation::class,
            'label' => 'Accreditations',
            'singular' => 'accreditation',
            'title' => ['numero_arrete', 'statut'],
            'search' => ['numero_arrete', 'type', 'statut'],
            'with' => ['institution'],
            'fields' => [
                'institution_id' => ['type' => 'relation', 'required' => true, 'model' => Institution::class, 'label_field' => 'nom'],
                'numero_arrete' => ['type' => 'text', 'required' => true, 'unique' => true],
                'date_arrete' => ['type' => 'date', 'required' => true],
                'date_debut' => ['type' => 'date', 'required' => true],
                'date_fin' => ['type' => 'date', 'required' => true],
                'type' => ['type' => 'select', 'required' => true, 'options' => ['creation' => 'Creation', 'renouvellement' => 'Renouvellement']],
                'statut' => ['type' => 'select', 'required' => true, 'options' => ['active' => 'Active', 'expiree' => 'Expiree', 'suspendue' => 'Suspendue']],
                'fichier_arrete_path' => ['type' => 'text'],
            ],
        ],
        'affectations' => [
            'model' => AffectationEnseignant::class,
            'label' => 'Affectations enseignants',
            'singular' => 'affectation',
            'title' => ['annee_universitaire', 'type_contrat'],
            'search' => ['annee_universitaire', 'type_contrat'],
            'with' => ['enseignant', 'institution', 'filiere'],
            'fields' => [
                'enseignant_id' => ['type' => 'relation', 'required' => true, 'model' => Enseignant::class, 'label_field' => 'nom_complet'],
                'institution_id' => ['type' => 'relation', 'required' => true, 'model' => Institution::class, 'label_field' => 'nom'],
                'filiere_id' => ['type' => 'relation', 'required' => true, 'model' => Filiere::class, 'label_field' => 'nom'],
                'volume_horaire' => ['type' => 'number', 'required' => true, 'min' => 0],
                'type_contrat' => ['type' => 'select', 'required' => true, 'options' => ['permanent' => 'Permanent', 'vacataire' => 'Vacataire']],
                'annee_universitaire' => ['type' => 'number', 'required' => true, 'min' => 1900],
            ],
        ],
        'calendriers' => [
            'model' => CalendrierAcademique::class,
            'label' => 'Calendriers academiques',
            'singular' => 'calendrier',
            'title' => ['annee_universitaire', 'statut'],
            'search' => ['annee_universitaire', 'statut'],
            'with' => ['institution'],
            'fields' => [
                'institution_id' => ['type' => 'relation', 'required' => true, 'model' => Institution::class, 'label_field' => 'nom'],
                'annee_universitaire' => ['type' => 'number', 'required' => true, 'min' => 1900],
                'debut_semestre_1' => ['type' => 'date', 'required' => true],
                'fin_semestre_1' => ['type' => 'date', 'required' => true],
                'debut_examens_s1' => ['type' => 'date', 'required' => true],
                'fin_examens_s1' => ['type' => 'date', 'required' => true],
                'debut_vacances_hiver' => ['type' => 'date'],
                'fin_vacances_hiver' => ['type' => 'date'],
                'debut_semestre_2' => ['type' => 'date', 'required' => true],
                'fin_semestre_2' => ['type' => 'date', 'required' => true],
                'debut_examens_s2' => ['type' => 'date', 'required' => true],
                'fin_examens_s2' => ['type' => 'date', 'required' => true],
                'debut_vacances_ete' => ['type' => 'date'],
                'fin_vacances_ete' => ['type' => 'date'],
                'statut' => ['type' => 'select', 'required' => true, 'options' => ['brouillon' => 'Brouillon', 'valide' => 'Valide', 'publie' => 'Publie']],
            ],
        ],
    ];

    public function index(Request $request, string $resource)
    {
        $config = $this->config($resource);
        $query = $config['model']::query()->with($config['with'] ?? []);

        if ($request->filled('q')) {
            $query->where(function ($builder) use ($request, $config) {
                foreach ($config['search'] ?? [] as $column) {
                    $builder->orWhere($column, 'like', '%' . $request->q . '%');
                }
            });
        }

        return view('admin.crud.index', [
            'resource' => $resource,
            'config' => $config,
            'items' => $query->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(string $resource)
    {
        return view('admin.crud.form', [
            'resource' => $resource,
            'config' => $this->config($resource),
            'item' => null,
            'options' => $this->relationOptions($resource),
        ]);
    }

    public function store(Request $request, string $resource)
    {
        $config = $this->config($resource);
        $config['model']::create($request->validate($this->rules($resource)));

        return redirect()->route('admin.resources.index', $resource)
            ->with('success', $config['singular'] . ' cree avec succes.');
    }

    public function show(string $resource, string $id)
    {
        $config = $this->config($resource);

        return view('admin.crud.show', [
            'resource' => $resource,
            'config' => $config,
            'item' => $this->find($config, $id),
        ]);
    }

    public function edit(string $resource, string $id)
    {
        $config = $this->config($resource);

        return view('admin.crud.form', [
            'resource' => $resource,
            'config' => $config,
            'item' => $this->find($config, $id),
            'options' => $this->relationOptions($resource),
        ]);
    }

    public function update(Request $request, string $resource, string $id)
    {
        $config = $this->config($resource);
        $item = $this->find($config, $id);
        $item->update($this->validatedData($request, $resource, $item));

        return redirect()->route('admin.resources.index', $resource)
            ->with('success', $config['singular'] . ' mis a jour.');
    }

    public function destroy(string $resource, string $id)
    {
        $config = $this->config($resource);
        $this->find($config, $id)->delete();

        return redirect()->route('admin.resources.index', $resource)
            ->with('success', $config['singular'] . ' supprime.');
    }

    private function config(string $resource): array
    {
        abort_unless(array_key_exists($resource, $this->resources), 404);

        return $this->resources[$resource];
    }

    private function find(array $config, string $id): Model
    {
        $model = new $config['model']();
        $query = $config['model']::query();

        if (Schema::hasColumn($model->getTable(), 'uuid')) {
            $query->where('uuid', $id)->orWhere('id', $id);
        } else {
            $query->where('id', $id);
        }

        return $query->firstOrFail();
    }

    private function rules(string $resource, ?Model $item = null): array
    {
        $config = $this->config($resource);
        $rules = [];

        foreach ($config['fields'] as $name => $field) {
            $fieldRules = [($field['required'] ?? false) && !($item && ($field['write_only'] ?? false)) ? 'required' : 'nullable'];

            if (($field['readonly_on_create'] ?? false) && !$item) {
                continue;
            }

            $fieldRules[] = match ($field['type']) {
                'email' => 'email',
                'date' => 'date',
                'number' => 'numeric',
                'relation' => 'integer',
                default => 'string',
            };

            if (isset($field['min'])) {
                $fieldRules[] = 'min:' . $field['min'];
            }

            if (isset($field['options'])) {
                $fieldRules[] = Rule::in(array_keys($field['options']));
            }

            if (($field['unique'] ?? false) === true) {
                $model = new $config['model']();
                $rule = Rule::unique($model->getTable(), $name);
                if ($item) {
                    $rule->ignore($item->getKey(), $item->getKeyName());
                }
                $fieldRules[] = $rule;
            }

            $rules[$name] = $fieldRules;
        }

        return $rules;
    }

    private function validatedData(Request $request, string $resource, ?Model $item = null): array
    {
        $config = $this->config($resource);
        $validated = $request->validate($this->rules($resource, $item));

        foreach ($config['fields'] as $name => $field) {
            if (($field['write_only'] ?? false) && ($validated[$name] ?? '') === '') {
                unset($validated[$name]);
            }
        }

        return $validated;
    }

    private function relationOptions(string $resource): array
    {
        $options = [];

        foreach ($this->config($resource)['fields'] as $name => $field) {
            if (($field['type'] ?? null) !== 'relation') {
                continue;
            }

            $labelField = $field['label_field'];
            $options[$name] = $field['model']::query()
                ->orderBy($labelField === 'nom_complet' ? 'nom' : $labelField)
                ->limit(500)
                ->get()
                ->mapWithKeys(fn ($item) => [$item->id => $item->{$labelField} ?? $item->nom ?? $item->name ?? $item->uuid])
                ->all();
        }

        return $options;
    }
}
