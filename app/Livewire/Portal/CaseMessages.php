<?php

namespace App\Livewire\Portal;

use App\Models\CaseRecord;
use App\Models\Message;
use Livewire\Component;

class CaseMessages extends Component
{
    public CaseRecord $case;
    public string $body = '';

    public function mount(CaseRecord $case): void
    {
        $this->case = $case;

        Message::where('case_record_id', $case->id)
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function send(): void
    {
        $this->validate([
            'body' => 'required|string|max:5000',
        ]);

        Message::create([
            'case_record_id' => $this->case->id,
            'sender_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->body = '';
    }

    public function render()
    {
        $messages = Message::where('case_record_id', $this->case->id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('livewire.portal.case-messages', compact('messages'));
    }
}
