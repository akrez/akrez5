<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Support\UserActiveBlog;
use App\View\Components\AkrezGridTable;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    protected function findQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Contact::filter(UserActiveBlog::name())
            ->orderDefault();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = static::findQuery()->paginate(50);
        $contactsGridTable = AkrezGridTable::build($contacts)
            ->newRawColumn('{{ App\Enums\ContactType::getValue($model->contact_type) }}', [], __('validation.attributes.contact_type'))
            ->newFieldColumn('title')
            ->newFieldColumn('content')
            ->newTagColumn('a', '{{ $model->link }}', function ($model) {
                return ['href' => $model->link,];
            }, __('validation.attributes.link'))
            ->newRawColumn('{{ App\Enums\ContactStatus::getValue($model->contact_status) }}', [], __('validation.attributes.status'))
            ->newFieldColumn('seq')
            ->newTagColumn('a', '@lang("Edit")', function ($model) {
                return [
                    'class' => "btn btn-info text-light w-100",
                    'href' => route('contacts.edit', ['contact' => $model,]),
                ];
            })
            ->newRawColumn('<form enctype="multipart/form-data" action="{{ $action }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger w-100">@lang("Delete")</button>
                </form>', function ($model) {
                return [
                    'action' => route('contacts.destroy', [
                        'contact' => $model,
                    ]),
                ];
            })
            ->render();

        return view('contacts.index', [
            'contacts' => $contacts,
            'contactsGridTable' => $contactsGridTable,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request)
    {
        $contact = new Contact($request->validated());
        $contact->blog_name = UserActiveBlog::name();
        $contact->created_by = Auth::id();
        $contact->save();

        return redirect()
            ->route('contacts.index')
            ->with('success', __('The :resource was created!', [
                'resource' => $contact->title,
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $contact = static::findQuery()->findOrFail($contact->id);
        return view('contacts.edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact = static::findQuery()->findOrFail($contact->id);
        $contact->update($request->all());
        $contact->blog_name = UserActiveBlog::name();
        $contact->created_by = Auth::id();
        $contact->save();
        return redirect()
            ->route('contacts.index')
            ->with('success', __('The :resource was updated!', [
                'resource' => $contact->title,
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact = static::findQuery()->findOrFail($contact->id);

        $contact->delete();

        return redirect()
            ->route('contacts.index')
            ->with('success', __('The file was deleted!', [
                'resource' => $contact->id,
            ]));
    }
}
