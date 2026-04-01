<?php
namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Business;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ClientManagement extends Component
{
    use WithPagination;
    public $formKey = 0;
    public $search = '';
    public $filterState = '';
    public $filterBusiness = '';
    public $filterPartner = '';
    public $showForm = false;
    public $editingClientId = null;
    public $client = [
        'client_name' => '',
        'phone_number' => '',
        'alternative_number' => '',
        'email' => '',
        'state' => '',
        'businesses' => [
            [
                'business_name' => '',
                'business_entity' => '',
                'nature_of_business' => '',
                'business_details' => '',
                'start_date' => '',
                'end_date' => '',
                'gst_number' => '',
                'pan_number' => '',
                'state' => '',
                'address' => '',
                'city' => '',
                'pincode' => '',
                'state_code' => '',
                'partners' => [
                    [
                        'partner_name' => '',
                        'partner_phone' => '',
                    ]

                ]
            ]
        ]
    ];
    //  Log::info('ClientManagement component mounted');
    //   Log::info('showCreateForm called');
    public $expandedClients = [];
    public function mount()
    {
        $this->ensureClientStructure();
    }
    protected function rules()                 // Log::info('addBusiness called', ['client' => $this->client]);
    {
        return [
            'client.client_name' => 'required|string|max:255',
            'client.phone_number' => 'required|numeric',
            'client.alternative_number' => 'nullable|string',
            'client.email' => 'nullable|email',
            'client.state' => 'required|string',
            'client.businesses' => 'required|array|min:1',
            'client.businesses.*.business_name' => 'required|string|max:255',
            'client.businesses.*.business_entity' => 'nullable|string|max:255',
            'client.businesses.*.nature_of_business' => 'nullable|string|max:255',
            'client.businesses.*.business_details' => 'nullable|string',
            'client.businesses.*.start_date' => 'nullable|date',
            'client.businesses.*.end_date' => 'nullable|date',
            'client.businesses.*.gst_number' => ['nullable','regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'client.businesses.*.pan_number' => ['nullable','regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'client.businesses.*.state' => 'required|string',
            'client.businesses.*.address' => 'nullable|string',
            'client.businesses.*.city' => 'nullable|string|max:255',
            'client.businesses.*.pincode' => 'nullable|string|max:20',
            'client.businesses.*.state_code' => 'nullable|string|max:20',
            'client.businesses.*.partners' => 'array',
            'client.businesses.*.partners.*.partner_name' => 'nullable|string|max:255',
            'client.businesses.*.partners.*.partner_phone' => 'nullable|string|max:20',
        ];
                     // Log::info('addPartner called', ['businessIndex' => $businessIndex, 'client' => $this->client]);
    }

    public function render()
    {
        $clients = Client::with(['businesses.partners'])
            ->when($this->search, function (Builder $query) {
                $query->where('client_name', 'like', "%{$this->search}%")
                    ->orWhere('phone_number', 'like', "%{$this->search}%");
            })
            ->when($this->filterState, fn($q) => $q->where('state', $this->filterState))
            ->when($this->filterBusiness, function ($q) {
                $q->whereHas('businesses', function ($b) {
                    $b->where('business_name', 'like', "%{$this->filterBusiness}%");
                        // Log::info('saveClient called', ['client' => $this->client, 'editingClientId' => $this->editingClientId]);
                });
            })
            ->when($this->filterPartner, function ($q) {
                                // Log::info('Updating existing client', ['editingClientId' => $this->editingClientId]);
                $q->whereHas('businesses.partners', function ($p) {
                    $p->where('partner_name', 'like', "%{$this->filterPartner}%");
                });
            })
                               // Log::info('Creating new client');
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.clients.client-management', [
            'clients' => $clients,
        ]);
    }

    public function showCreateForm()
    {
        $this->reset('client');
        $this->client = $this->emptyClient();
        $this->showForm = true;
        $this->editingClientId = null;
        $this->formKey++;
    }

    public function addBusiness()
    {
        $this->ensureClientStructure();
        $client = $this->client;
        $client['businesses'][] = $this->emptyBusiness();
        $this->client = $client;
        $this->formKey++;
    }

    public function removeBusiness($index)
    {
        $this->ensureClientStructure();
        $client = $this->client;
        unset($client['businesses'][$index]);
        $client['businesses'] = array_values($client['businesses']);
        if (empty($client['businesses'])) {
            $client['businesses'][] = $this->emptyBusiness();
        }
        $this->client = $client;
        $this->formKey++;
    }

    public function addPartner($businessIndex)
    {
        $this->ensureClientStructure();
        $client = $this->client;

        if (! isset($client['businesses'][$businessIndex])) {
            return;
        }

        if (! isset($client['businesses'][$businessIndex]['partners']) || ! is_array($client['businesses'][$businessIndex]['partners'])) {
            $client['businesses'][$businessIndex]['partners'] = [];
        }

        $client['businesses'][$businessIndex]['partners'][] = $this->emptyPartner();
        $this->client = $client;
        $this->formKey++;
    }

    public function removePartner($businessIndex, $partnerIndex)
    {
        $this->ensureClientStructure();
        $client = $this->client;

        if (! isset($client['businesses'][$businessIndex]['partners'][$partnerIndex])) {
            return;
        }

        unset($client['businesses'][$businessIndex]['partners'][$partnerIndex]);
        $client['businesses'][$businessIndex]['partners'] = array_values(
            $client['businesses'][$businessIndex]['partners']
        );

        if (empty($client['businesses'][$businessIndex]['partners'])) {
            $client['businesses'][$businessIndex]['partners'][] = $this->emptyPartner();
        }

        $this->client = $client;
        $this->formKey++;
    }

    public function saveClient()
    {
        $this->validate();
        DB::transaction(function () {
            if ($this->editingClientId) {
                $client = Client::findOrFail($this->editingClientId);
                $client->update($this->onlyClientFields());
                $client->businesses()->delete();
            } else {
                $client = Client::create($this->onlyClientFields());
            }
            foreach ($this->client['businesses'] as $businessData) {
                $partners = $businessData['partners'] ?? [];
                unset($businessData['partners']);
                $business = $client->businesses()->create($businessData);
                foreach ($partners as $partnerData) {
                    $business->partners()->create($partnerData);
                }
            }
        });
        $this->resetForm();
        $this->showForm = false;
        session()->flash('success', 'Client saved successfully!');
    }

    public function editClient($clientId)
    {
        $client = Client::with('businesses.partners')->findOrFail($clientId);
        $this->editingClientId = $client->id;
        $businesses = $client->businesses->map(function ($business) {
            return [
                'business_name' => $business->business_name ?? '',
                'business_entity' => $business->business_entity ?? '',
                'nature_of_business' => $business->nature_of_business ?? '',
                'business_details' => $business->business_details ?? '',
                'start_date' => $business->start_date ?? '',
                'end_date' => $business->end_date ?? '',
                'gst_number' => $business->gst_number ?? '',
                'pan_number' => $business->pan_number ?? '',
                'state' => $business->state ?? '',
                'address' => $business->address ?? '',
                'city' => $business->city ?? '',
                'pincode' => $business->pincode ?? '',
                'state_code' => $business->state_code ?? '',
                'partners' => $business->partners->map(function ($partner) {
                    return [
                        'partner_name' => $partner->partner_name ?? '',
                        'partner_phone' => $partner->partner_phone ?? '',
                    ];
                })->toArray(),
            ];
        })->toArray();
        $this->client = [
            'client_name' => $client->client_name ?? '',
            'phone_number' => $client->phone_number ?? '',
            'alternative_number' => $client->alternative_number ?? '',
            'email' => $client->email ?? '',
            'state' => $client->state ?? '',
            'businesses' => ! empty($businesses) ? $businesses : [$this->emptyBusiness()],
        ];
        $this->showForm = true;
        $this->formKey++;
    }

    public function deleteClient($clientId)
    {
        DB::transaction(function () use ($clientId) {
            $client = Client::findOrFail($clientId);
            $client->delete();
        });
        session()->flash('success', 'Client deleted successfully!');
    }

    public function toggleExpand($clientId)
    {
        if (in_array($clientId, $this->expandedClients)) {
            $this->expandedClients = array_diff($this->expandedClients, [$clientId]);
        } else {
            $this->expandedClients[] = $clientId;
        }
    }

    private function onlyClientFields()
    {
        return [
            'client_name' => $this->client['client_name'],
            'phone_number' => $this->client['phone_number'],
            'alternative_number' => $this->client['alternative_number'],
            'email' => $this->client['email'],
            'state' => $this->client['state'],
        ];
    }

    private function resetForm()
    {
        $this->client = $this->emptyClient();
        $this->editingClientId = null;
        $this->formKey++;
    }

    private function ensureClientStructure(): void
    {
        if (! isset($this->client['businesses']) || ! is_array($this->client['businesses']) || empty($this->client['businesses'])) {
            $this->client['businesses'] = [$this->emptyBusiness()];
        }

        foreach ($this->client['businesses'] as $businessIndex => $business) {
            if (! isset($business['partners']) || ! is_array($business['partners']) || empty($business['partners'])) {
                $this->client['businesses'][$businessIndex]['partners'] = [$this->emptyPartner()];
            }
        }
    }

    private function emptyClient(): array
    {
        return [
            'client_name' => '',
            'phone_number' => '',
            'alternative_number' => '',
            'email' => '',
            'state' => '',
            'businesses' => [$this->emptyBusiness()],
        ];
    }

    private function emptyBusiness(): array
    {
        return [
            'business_name' => '',
            'business_entity' => '',
            'nature_of_business' => '',
            'business_details' => '',
            'start_date' => '',
            'end_date' => '',
            'gst_number' => '',
            'pan_number' => '',
            'state' => '',
            'address' => '',
            'city' => '',
            'pincode' => '',
            'state_code' => '',
            'partners' => [$this->emptyPartner()],
        ];
    }

    private function emptyPartner(): array
    {
        return [
            'partner_name' => '',
            'partner_phone' => '',
        ];
    }
}
