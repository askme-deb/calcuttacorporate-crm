<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImageUploadComponent extends Component
{

    use WithFileUploads;

    public $files = [];

    public function save()
    {
        $this->validate([
            'files.*' => 'required|file|max:10240', // 10MB max file size
        ]);

        foreach ($this->files as $file) {
            $file->store('uploads'); // Change the path as needed
        }

        session()->flash('message', 'Files uploaded successfully.');
        $this->reset('files'); // Clear the input after upload
    }


    // use WithFileUploads;

    // public $file;

    // public function updatedFile()
    // {
    //     $this->validate([
    //         'file' => 'required|file|max:2048', // 2MB Max
    //     ]);
    // }

    // public function save()
    // {
    //     $this->validate([
    //         'file' => 'required|file|max:2048',
    //     ]);

    //     $path = $this->file->store('uploads', 'public');

    //     session()->flash('message', 'File uploaded successfully!');

    //     // Reset file input
    //     $this->reset('file');
    // }

    public function render()
    {
        //Get Uploaded Images

       // $images = Image::orderBy('id','DESC')->get();

        return view('livewire.image-upload-component');
    }

}
