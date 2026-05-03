<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use App\Models\Lead;
use App\Models\LeadTag;
use Illuminate\Support\Str;

class LeadTagManager extends Component
{
    public $leadId;
    public $tags = [];
    public $newTag = '';
    public $suggestions = [];
    public $showSuggestions = false;

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->tags = Lead::findOrFail($leadId)->tags()->pluck('tag')->toArray();
    }

    public function loadSuggestions()
    {

        $this->showSuggestions = true;
        $this->suggestions = $this->fetchSuggestions($this->newTag);
    }

    public function updatedNewTag($value)
    {
        $this->showSuggestions = true;
        $this->suggestions = $this->fetchSuggestions($value);
    }

    private function fetchSuggestions($value)
    {
        $term = trim($value);

        $query = LeadTag::query()
            ->select('tag')
            ->whereRaw('TRIM(tag) <> ""')
            ->distinct();

        if ($term !== '') {
            $query->where('tag', 'like', '%' . $term . '%');
        }

        return $query->orderBy('tag')
            ->limit(50)
            ->pluck('tag')
            ->unique(fn ($tag) => Str::lower(trim($tag)))
            ->take(10)
            ->values()
            ->toArray();
    }

    public function selectSuggestion($tag)
    {
        $this->newTag = $tag;
        $this->suggestions = [];
        $this->showSuggestions = false;
    }

    public function hideSuggestions()
    {
        $this->showSuggestions = false;
    }

    public function addTag()
    {
        $this->validate(['newTag' => 'required|string|max:100']);

        $tag = trim($this->newTag);
        $normalizedTag = Str::lower($tag);
        $normalizedCurrentTags = collect($this->tags)
            ->map(fn ($existingTag) => Str::lower(trim($existingTag)))
            ->filter()
            ->all();

        if (in_array($normalizedTag, $normalizedCurrentTags, true)) {
            $this->addError('newTag', 'This tag is already added.');
            return;
        }

        LeadTag::create([
            'lead_id' => $this->leadId,
            'tag' => $tag,
        ]);
        $this->mount($this->leadId);
        $this->reset('newTag');
        $this->suggestions = [];
    }

    public function removeTag($tag)
    {
        LeadTag::where('lead_id', $this->leadId)->where('tag', $tag)->delete();
        $this->mount($this->leadId);
    }

    public function render()
    {
        return view('livewire.leads.lead-tag-manager');
    }
}
