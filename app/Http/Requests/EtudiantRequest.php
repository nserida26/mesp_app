<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EtudiantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date|before:-17 years',
            'lieu_naissance' => 'required|string|max:255',
            'numero_national' => 'required|string|unique:etudiants,numero_national',
            'numero_bac' => 'required|string|min:5',
            'serie_bac' => 'nullable|string|max:50',
            'annee_bac' => 'required|digits:4|integer|min:2000|max:' . (date('Y')),
            'mention_bac' => 'nullable|string|max:50',
            'email' => 'required|email|unique:etudiants,email',
            'telephone' => 'required|string|max:20',
            'filiere_id' => 'nullable|exists:filieres,id'
        ];

        // Pour l'update, on ignore l'étudiant courant
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $etudiantId = $this->route('uuid');
            $rules['email'] = ['required', 'email', Rule::unique('etudiants')->ignore($etudiantId, 'uuid')];
            $rules['numero_national'] = ['required', 'string', Rule::unique('etudiants')->ignore($etudiantId, 'uuid')];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'date_naissance.before' => 'L\'étudiant doit avoir au moins 17 ans.',
            'annee_bac.max' => 'L\'année du baccalauréat ne peut pas être dans le futur.',
            'numero_national.unique' => 'Ce numéro national est déjà utilisé.',
            'email.unique' => 'Cette adresse email est déjà utilisée.'
        ];
    }

    protected function prepareForValidation()
    {
        // Hasher automatiquement le numéro de bac
        if ($this->has('numero_bac')) {
            $this->merge([
                'hash_numero_bac' => hash('sha256', $this->numero_bac)
            ]);
        }
    }
}
