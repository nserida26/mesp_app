<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampagneOrientationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_orientation' => 'required|in:bac_licence,licence_master',
            'nom' => 'required|string|max:255',
            'annee_universitaire' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'date_ouverture' => 'required|date',
            'date_fermeture' => 'required|date|after:date_ouverture',
            'date_publication_resultats' => 'nullable|date|after_or_equal:date_fermeture',
            'nombre_max_choix' => 'required|integer|min:1|max:20',
            'cni_requis' => 'boolean',
            'releve_notes_requis' => 'boolean',
            'diplome_requis' => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cni_requis' => $this->boolean('cni_requis'),
            'releve_notes_requis' => $this->boolean('releve_notes_requis'),
            'diplome_requis' => $this->boolean('diplome_requis'),
        ]);
    }
}
