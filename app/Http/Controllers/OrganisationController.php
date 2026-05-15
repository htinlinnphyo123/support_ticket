<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganisationCreateRequest;
use App\Http\Requests\OrganisationUpdateRequest;
use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class OrganisationController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Organisation::class);
        $organisations = Organisation::with('creator')->paginate(10);

        return Inertia::render('Organisations/Index', [
            'organisations' => OrganisationResource::collection($organisations),
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Organisation::class);

        return Inertia::render('Organisations/Create');
    }

    public function store(OrganisationCreateRequest $request)
    {
        Gate::authorize('create', Organisation::class);
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        Organisation::create($data);

        return redirect()->route('organisations.index')->with('success', 'Organisation created successfully.');
    }

    public function show(Organisation $organisation)
    {
        Gate::authorize('view', $organisation);
        $organisation->load('creator', 'users');

        return Inertia::render('Organisations/Show', [
            'organisation' => (new OrganisationResource($organisation))->resolve(),
        ]);
    }

    public function edit(Organisation $organisation)
    {
        Gate::authorize('update', $organisation);

        return Inertia::render('Organisations/Edit', [
            'organisation' => (new OrganisationResource($organisation))->resolve(),
        ]);
    }

    public function update(OrganisationUpdateRequest $request, Organisation $organisation)
    {
        Gate::authorize('update', $organisation);
        $organisation->update($request->validated());

        return redirect()->route('organisations.index')->with('success', 'Organisation updated successfully.');
    }

    public function destroy(Organisation $organisation)
    {
        Gate::authorize('delete', $organisation);
        $organisation->delete();

        return redirect()->route('organisations.index')->with('success', 'Organisation deleted successfully.');
    }
}
