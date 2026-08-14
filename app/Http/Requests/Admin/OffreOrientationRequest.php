<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OffreOrientationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $campagne = $this->campagneCible();

        return [
            'campagne_orientation_id' => 'required|exists:campagnes_orientation,id',
            'filiere_id' => [
                'required',
                Rule::exists('filieres', 'id')->where(
                    'niveau',
                    $campagne?->type_orientation === 'licence_master' ? 'master' : 'licence'
                ),
                Rule::unique('offres_orientation', 'filiere_id')
                    ->where('campagne_orientation_id', $this->input('campagne_orientation_id'))
                    ->ignore($this->route('offre')?->id),
            ],
            'capacite' => 'required|integer|min:1',
            'moyenne_minimale' => 'required|numeric|min:0|max:20',
            'statut' => 'required|in:active,inactive',
            'types_bac' => 'array|required_if:campagne_type,bac_licence',
            'types_bac.*' => 'exists:types_bac,id',
            'domaines_licence' => 'array|required_if:campagne_type,licence_master',
            'domaines_licence.*' => 'exists:domaines_licence,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $campagne = $this->campagneCible();

        $this->merge([
            'campagne_type' => $campagne?->type_orientation,
        ]);
    }

    private function campagneCible(): ?\App\Models\CampagneOrientation
    {
        $id = $this->input('campagne_orientation_id') ?? $this->route('campagne');

        if (!$id) {
            return $this->route('offre')?->campagne;
        }

        return \App\Models\CampagneOrientation::query()->where('id', $id)->orWhere('uuid', $id)->first();
    }
}
