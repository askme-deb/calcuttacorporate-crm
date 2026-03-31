<?php

namespace App\Livewire\Documents;

use App\Models\ListOfDocument as ModelsListOfDocument;
use Livewire\Component;

class Listofdocument extends Component
{

    public $name;
    public $parent_id;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:list_of_documents,name',
            'parent_id' => 'nullable|exists:list_of_documents,id',
        ]);

        ModelsListOfDocument::create([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
        ]);

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Document added successfully'
        ]));

        $this->reset();
    }





    public function render()
    {
        $categories = ModelsListOfDocument::whereNull('parent_id')->with('children')->get();
        return view('livewire.documents.listofdocument', compact('categories'));
    }
}
