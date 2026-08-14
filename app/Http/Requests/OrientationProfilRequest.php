<?php

namespace App\Http\Requests;

use App\Models\CampagneOrientation;
use Illuminate\Foundation\Http\FormRequest;

class OrientationProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->route('type');
        $campagne = $this->campagne();

        $commun = [
            'nom_complet' => 'required|string|max:255',
            'nni' => 'required|string|min:6|max:20',
            'telephone' => 'nullable|regex:/^\+222[0-9]{8}$/',
            'email' => 'nullable|email|max:255',
            'moyenne_generale' => 'required|numeric|min:0|max:20',
            'annee_obtention' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];

        $specifique = $type === 'bac-licence'
            ? ['type_bac_id' => 'required|exists:types_bac,id']
            : ['domaine_licence_id' => 'required|exists:domaines_licence,id'];

        $fichiers = [
            'cni' => (($campagne?->cni_requis ?? false) ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            'releve_notes' => (($campagne?->releve_notes_requis ?? false) ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            'diplome' => (($campagne?->diplome_requis ?? false) ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
        ];

        return array_merge($commun, $specifique, $fichiers);
    }

    public function campagne(): ?CampagneOrientation
    {
        $type = $this->route('type') === 'bac-licence' ? 'bac_licence' : 'licence_master';

        return CampagneOrientation::query()
            ->where('type_orientation', $type)
            ->where('statut', 'active')
            ->where('date_ouverture', '<=', now())
            ->where('date_fermeture', '>=', now())
            ->first();
    }
}
