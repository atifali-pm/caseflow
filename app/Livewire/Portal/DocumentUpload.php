<?php

namespace App\Livewire\Portal;

use App\Models\CaseRecord;
use App\Models\Document;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUpload extends Component
{
    use WithFileUploads;

    public CaseRecord $case;
    public $file;

    public function mount(CaseRecord $case): void
    {
        $this->case = $case;
    }

    public function upload(): void
    {
        $this->validate([
            'file' => 'required|file|max:10240',
        ]);

        $path = $this->file->store('documents', 'local');

        Document::create([
            'documentable_type' => CaseRecord::class,
            'documentable_id' => $this->case->id,
            'filename' => $this->file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'local',
            'mime_type' => $this->file->getMimeType(),
            'size' => $this->file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        $this->file = null;
        $this->dispatch('document-uploaded');
    }

    public function render()
    {
        return view('livewire.portal.document-upload');
    }
}
