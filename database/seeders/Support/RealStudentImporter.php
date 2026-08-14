<?php

namespace Database\Seeders\Support;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Parses the real institution student-list spreadsheets (and the pre-extracted
 * Sup'Management PDF dataset) into a single normalized record shape consumed
 * by DemoDataSeeder. Source files live in database/seeders/data/ and are
 * gitignored since they contain real students' names and national ID numbers.
 */
class RealStudentImporter
{
    private string $dataPath;

    public function __construct(?string $dataPath = null)
    {
        $this->dataPath = $dataPath ?? database_path('seeders/data');
    }

    /**
     * @return array<string, array<int, array>> records grouped by institution code
     */
    public function all(): array
    {
        $records = [
            'GEU' => [],
            'LIU' => [],
            'ISB' => [],
            'ISI' => [],
            'EDGE' => [],
            'SUPM' => [],
        ];

        if (is_file("{$this->dataPath}/geu.xlsx")) {
            $records['GEU'] = $this->parseCanava("{$this->dataPath}/geu.xlsx", 'GEU', 'Spécialité');
        }
        if (is_file("{$this->dataPath}/liu.xlsx")) {
            $records['LIU'] = $this->parseCanava("{$this->dataPath}/liu.xlsx", 'LIU', 'Filière');
        }
        if (is_file("{$this->dataPath}/isb.xlsx")) {
            $records['ISB'] = $this->parseIsb("{$this->dataPath}/isb.xlsx");
        }
        if (is_file("{$this->dataPath}/isi.xlsx")) {
            $records['ISI'] = $this->parseIsi("{$this->dataPath}/isi.xlsx");
        }
        if (is_file("{$this->dataPath}/edge.xlsx")) {
            $records['EDGE'] = $this->parseEdge("{$this->dataPath}/edge.xlsx");
        }
        if (is_file("{$this->dataPath}/supm.json")) {
            $records['SUPM'] = $this->parseSupmJson("{$this->dataPath}/supm.json");
        }

        return $records;
    }

    // ── Canava-format files (GEU, LIU): N° | Matricule | Nom et prénom | NNI | N° BAC | Année | Niveau | Spécialité | Filière | Serie Bac | Etablissement | Nationalité ──

    private function parseCanava(string $path, string $institutionCode, string $filiereSourceCol): array
    {
        $rows = $this->loadRows($path);
        $headerIdx = $this->findHeaderRow($rows, ['nni', 'nom']);
        $map = $this->headerMap($rows[$headerIdx]);

        $out = [];
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nomComplet = $this->cell($row, $map, 'Nom et prénom');
            $niveauRaw = $this->cell($row, $map, 'Niveau');
            $filiereRaw = $this->cell($row, $map, $filiereSourceCol);

            if (!$nomComplet || !$niveauRaw || !$filiereRaw) continue;

            $niveau = $this->normalizeNiveau($niveauRaw);
            if (!$niveau) continue;

            [$nom, $prenom] = $this->splitName($nomComplet);
            $annee = $this->cell($row, $map, 'Année');

            $out[] = [
                'nom' => $nom,
                'prenom' => $prenom,
                'numero_national' => $this->cleanNni($this->cell($row, $map, 'NNI')),
                'numero_bac' => $this->cell($row, $map, 'N° BAC'),
                'annee_bac' => $annee ? (int) $annee : null,
                'serie_bac' => $this->cell($row, $map, 'Serie Bac'),
                'filiere_nom' => $this->canonicalFiliere($institutionCode, $filiereRaw),
                'filiere_niveau' => $niveau['niveau'],
                'semestre_courant' => $niveau['semestre'],
                'nationalite' => $this->cell($row, $map, 'Nationalité'),
                'date_naissance' => null,
            ];
        }

        return $out;
    }

    // ── ISB: N° | Idantifiant | Nom et prénom | NNI | N° BAC | Année | Niveau (free text "Master X") | Filière (domaine) | Spécialité | Serie Bac | Etablissement | Nationalité ──

    private function parseIsb(string $path): array
    {
        $rows = $this->loadRows($path);
        $headerIdx = $this->findHeaderRow($rows, ['nni', 'nom']);
        $map = $this->headerMap($rows[$headerIdx]);

        $out = [];
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nomComplet = $this->cell($row, $map, 'Nom et prénom');
            $niveauRaw = $this->cell($row, $map, 'Niveau');
            $filiereRaw = $this->cell($row, $map, 'Spécialité');

            if (!$nomComplet || !$niveauRaw || !$filiereRaw) continue;

            $niveau = $this->normalizeNiveau($niveauRaw);
            if (!$niveau) continue;

            [$nom, $prenom] = $this->splitName($nomComplet);
            $annee = $this->cell($row, $map, 'Année');

            $out[] = [
                'nom' => $nom,
                'prenom' => $prenom,
                'numero_national' => $this->cleanNni($this->cell($row, $map, 'NNI')),
                'numero_bac' => $this->cell($row, $map, 'N° BAC'),
                'annee_bac' => $annee ? (int) $annee : null,
                'serie_bac' => $this->cell($row, $map, 'Serie Bac'),
                'filiere_nom' => $this->canonicalFiliere('ISB', $filiereRaw),
                'filiere_niveau' => $niveau['niveau'],
                'semestre_courant' => $niveau['semestre'],
                'nationalite' => $this->cell($row, $map, 'Nationalité'),
                'date_naissance' => null,
            ];
        }

        return $out;
    }

    // ── ISI: bilingual admin export. French header row contains NUMERO D'INSCRIPTION | NNI | N° de BAC | Nom et prénom | GENRE | DATE DE NAISSANCE | ... | Niveau (...) | ... | NOM DU (TRONC/FILIRERE/...) | ... | NATIONALITE ──

    private function parseIsi(string $path): array
    {
        $rows = $this->loadRows($path);
        $headerIdx = $this->findHeaderRow($rows, ['nni', "nom et pr"]);
        $fuzzy = $this->fuzzyHeaderMap($rows[$headerIdx]);

        $iNni = $this->findFuzzyCol($fuzzy, 'nni');
        $iNom = $this->findFuzzyCol($fuzzy, 'nom et pr');
        $iBac = $this->findFuzzyCol($fuzzy, 'bac');
        $iNiveau = $this->findFuzzyCol($fuzzy, 'niveau');
        $iFiliere = $this->findFuzzyCol($fuzzy, 'tronc');
        $iNat = $this->findFuzzyCol($fuzzy, 'nationalit');
        $iNaiss = $this->findFuzzyCol($fuzzy, 'naissance');

        $out = [];
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nomComplet = $this->rawCell($row, $iNom);
            $niveauRaw = $this->rawCell($row, $iNiveau);
            $filiereRaw = $this->rawCell($row, $iFiliere);

            if (!$nomComplet || !$niveauRaw || !$filiereRaw) continue;

            $niveau = $this->normalizeNiveau($niveauRaw);
            if (!$niveau) continue;

            [$nom, $prenom] = $this->splitName($nomComplet);

            $out[] = [
                'nom' => $nom,
                'prenom' => $prenom,
                'numero_national' => $this->cleanNni($this->rawCell($row, $iNni)),
                'numero_bac' => $this->rawCell($row, $iBac),
                'annee_bac' => null,
                'serie_bac' => null,
                'filiere_nom' => $this->canonicalFiliere('ISI', $filiereRaw),
                'filiere_niveau' => $niveau['niveau'],
                'semestre_courant' => $niveau['semestre'],
                'nationalite' => $this->rawCell($row, $iNat),
                'date_naissance' => $this->parseUsDate($this->rawCell($row, $iNaiss)),
            ];
        }

        return $out;
    }

    // ── Student Edge Mauritania: # | Student ID | Name | Program | Session | Semester | Section | Status ──
    // No NNI in this source; numero_national stays null for these students.

    private function parseEdge(string $path): array
    {
        $rows = $this->loadRows($path);
        $headerIdx = $this->findHeaderRow($rows, ['student id', 'name']);
        $fuzzy = $this->fuzzyHeaderMap($rows[$headerIdx]);

        $iName = $this->findFuzzyCol($fuzzy, 'name');
        $iProgram = $this->findFuzzyCol($fuzzy, 'program');
        $iSemester = $this->findFuzzyCol($fuzzy, 'semester');

        $programNames = ['TC' => 'Tronc Commun', 'MCD' => 'Management, Communication et Digital'];

        $out = [];
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nomComplet = $this->rawCell($row, $iName);
            $program = $this->rawCell($row, $iProgram);
            $semesterRaw = $this->rawCell($row, $iSemester);

            if (!$nomComplet || !$program) continue;

            $semestre = 1;
            $niveau = 'licence';
            if ($semesterRaw && preg_match('/(\d+)/', $semesterRaw, $m)) {
                $semestre = (int) $m[1];
                $niveau = $semestre > 6 ? 'master' : 'licence';
            }

            [$nom, $prenom] = $this->splitName($nomComplet);

            $out[] = [
                'nom' => $nom,
                'prenom' => $prenom,
                'numero_national' => null,
                'numero_bac' => null,
                'annee_bac' => null,
                'serie_bac' => null,
                'filiere_nom' => $programNames[$program] ?? $program,
                'filiere_niveau' => $niveau,
                'semestre_courant' => $semestre,
                'nationalite' => null,
                'date_naissance' => null,
            ];
        }

        return $out;
    }

    // ── Sup'Management: pre-extracted from the source PDF (no parser dependency at runtime) ──

    private function parseSupmJson(string $path): array
    {
        $rows = json_decode(file_get_contents($path), true) ?? [];

        return array_map(fn (array $r) => [
            'nom' => $r['nom'],
            'prenom' => $r['prenom'],
            'numero_national' => $this->cleanNni($r['numero_national']),
            'numero_bac' => $r['numero_bac'],
            'annee_bac' => $r['annee_bac'],
            'serie_bac' => $r['serie_bac'],
            'filiere_nom' => $r['filiere_nom'],
            'filiere_niveau' => $r['filiere_niveau'],
            'semestre_courant' => $r['semestre_courant'],
            'nationalite' => $r['nationalite'],
            'date_naissance' => null,
        ], $rows);
    }

    // ── Shared helpers ──

    private function loadRows(string $path): array
    {
        return IOFactory::load($path)->getSheet(0)->toArray(null, true, true, false);
    }

    private function findHeaderRow(array $rows, array $mustContain): int
    {
        foreach ($rows as $i => $row) {
            $joined = mb_strtolower(implode('|', array_map(fn ($v) => (string) $v, $row)));
            $ok = true;
            foreach ($mustContain as $needle) {
                if (!str_contains($joined, mb_strtolower($needle))) { $ok = false; break; }
            }
            if ($ok) return $i;
        }

        throw new \RuntimeException("Header row not found in source file");
    }

    private function headerMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $idx => $label) {
            $label = trim((string) $label);
            if ($label !== '') $map[$label] = $idx;
        }

        return $map;
    }

    private function fuzzyHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $idx => $label) {
            $label = trim((string) $label);
            if ($label !== '') $map[$idx] = mb_strtolower($label);
        }

        return $map;
    }

    private function findFuzzyCol(array $fuzzyMap, string $needle): ?int
    {
        foreach ($fuzzyMap as $idx => $label) {
            if (str_contains($label, mb_strtolower($needle))) return $idx;
        }

        return null;
    }

    private function cell(array $row, array $map, string $name): ?string
    {
        return isset($map[$name]) ? $this->rawCell($row, $map[$name]) : null;
    }

    private function rawCell(array $row, ?int $idx): ?string
    {
        if ($idx === null) return null;
        $v = $row[$idx] ?? null;
        if ($v === null) return null;
        $v = trim(preg_replace('/\s+/', ' ', (string) $v));

        return $v === '' ? null : $v;
    }

    private function cleanNni(?string $nni): ?string
    {
        if (!$nni) return null;
        if (preg_match('/^(malien|malienne|mauritanien|mauritanienne)$/iu', $nni)) return null;

        return $nni;
    }

    private function parseUsDate(?string $value): ?string
    {
        if (!$value) return null;
        try {
            return Carbon::createFromFormat('n/j/Y', $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array{niveau: string, semestre: int}|null
     */
    private function normalizeNiveau(string $raw): ?array
    {
        $raw = trim($raw);

        if (preg_match('/^([LM])\s*-?\s*([1-3])$/iu', $raw, $m)) {
            $level = (int) $m[2];
            $isMaster = strtoupper($m[1]) === 'M';

            return [
                'niveau' => $isMaster ? 'master' : 'licence',
                'semestre' => $isMaster ? ($level === 1 ? 1 : 3) : (($level - 1) * 2 + 1),
            ];
        }

        if (preg_match('/licen[cs]e\s*(\d)?/iu', $raw, $m)) {
            $level = isset($m[1]) && $m[1] !== '' ? (int) $m[1] : 1;

            return ['niveau' => 'licence', 'semestre' => ($level - 1) * 2 + 1];
        }

        if (preg_match('/master/iu', $raw)) {
            return ['niveau' => 'master', 'semestre' => 1];
        }

        return null;
    }

    /**
     * @return array{0: string, 1: string} [nom, prenom]
     */
    private function splitName(string $full): array
    {
        $full = trim(preg_replace('/\s+/', ' ', $full));
        $words = explode(' ', $full);

        if (count($words) === 1) {
            return [$words[0], $words[0]];
        }

        $isUpper = fn ($w) => mb_strtoupper($w, 'UTF-8') === $w && preg_match('/[A-Za-zÀ-ÿ]/u', $w);
        $wholeUpper = mb_strtoupper($full, 'UTF-8') === $full;

        if (!$wholeUpper) {
            $i = count($words) - 1;
            $tail = [];
            while ($i >= 1 && $isUpper($words[$i])) {
                array_unshift($tail, $words[$i]);
                $i--;
            }
            if (!empty($tail)) {
                return [implode(' ', $tail), implode(' ', array_slice($words, 0, $i + 1))];
            }
        }

        return [implode(' ', array_slice($words, 1)), $words[0]];
    }

    private function canonicalFiliere(string $institutionCode, string $raw): string
    {
        $raw = trim(preg_replace('/\s+/', ' ', $raw));
        $key = mb_strtolower($raw);

        $map = [
            'LIU' => [
                'comptabilité et audit' => 'Comptabilité Finance et Audit',
                'comptabilité, finances et audit' => 'Comptabilité Finance et Audit',
                'gestion internationle des affaires' => 'Gestion Internationale des Affaires',
                'getion internationale des affaires' => 'Gestion Internationale des Affaires',
                'informatiques appliquées' => 'Informatique Appliquées',
                'commerce internationale et transit' => 'Commerce International et Transit',
            ],
            'ISI' => [
                'commerce intrnational' => 'Commerce International',
                'informatique' => 'Informatiques',
                'information de gestion' => 'Informatique de Gestion',
            ],
        ];

        return $map[$institutionCode][$key] ?? $raw;
    }
}
