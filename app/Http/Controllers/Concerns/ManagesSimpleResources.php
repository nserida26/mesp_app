<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait ManagesSimpleResources
{
    public function index(Request $request)
    {
        $query = ($this->modelClass)::query()->with($this->with);

        if ($request->filled('search') && $this->searchable) {
            $query->where(function ($builder) use ($request) {
                foreach ($this->searchable as $column) {
                    $builder->orWhere($column, 'like', '%' . $request->search . '%');
                }
            });
        }

        $items = $query->latest()->paginate(15)->withQueryString();

        return view('crud.index', [
            'items' => $items,
            'resourceName' => $this->resourceName,
            'routeName' => $this->routeName(),
        ]);
    }

    public function create()
    {
        return view('crud.form', [
            'item' => null,
            'resourceName' => $this->resourceName,
            'routeName' => $this->routeName(),
            'fields' => array_keys($this->validationRules),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules);
        ($this->modelClass)::create($validated);

        return redirect()->route($this->routeName() . '.index')
            ->with('success', "{$this->resourceName} cree avec succes.");
    }

    public function show($id)
    {
        $item = $this->findModel($id);

        return view('crud.show', [
            'item' => $item,
            'resourceName' => $this->resourceName,
            'routeName' => $this->routeName(),
        ]);
    }

    public function edit($id)
    {
        $item = $this->findModel($id);

        return view('crud.form', [
            'item' => $item,
            'resourceName' => $this->resourceName,
            'routeName' => $this->routeName(),
            'fields' => array_keys($this->validationRules),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->findModel($id);
        $item->update($request->validate($this->validationRules));

        return redirect()->route($this->routeName() . '.index')
            ->with('success', "{$this->resourceName} mis a jour.");
    }

    public function destroy($id)
    {
        $this->findModel($id)->delete();

        return redirect()->route($this->routeName() . '.index')
            ->with('success', "{$this->resourceName} supprime.");
    }

    protected function findModel($id)
    {
        return ($this->modelClass)::query()
            ->where('uuid', $id)
            ->orWhere('id', $id)
            ->firstOrFail();
    }

    protected function routeName(): string
    {
        return str($this->resourceName)->lower()->ascii()->plural()->toString();
    }
}
