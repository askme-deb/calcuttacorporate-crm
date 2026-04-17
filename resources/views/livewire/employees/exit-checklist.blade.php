<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Exit Checklist</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Exit Checklist for {{ $resignation->employee->name ?? 'Employee' }}</h4>
                    </div>
                </div>
            </div>
              
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            {{-- Completion Status --}}
                            <div class="mb-4">
                                <div class="mb-2 fw-medium">Completion: {{ $this->completionPercentage }}%</div>
                                <div class="progress" style="height: 1rem;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: {{ $this->completionPercentage }}%"
                                         aria-valuenow="{{ $this->completionPercentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                             
                            {{-- Checklist Form --}}
                            <form wire:submit.prevent="updateChecklist">
                                @foreach ($checklist as $field => $value)
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="{{ $field }}"
                                           wire:model.defer="checklist.{{ $field }}">
                                    <label class="form-check-label" for="{{ $field }}">
                                        {{ $checklistLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}
                                    </label>
                                </div>
                                @endforeach
                                 
                                <div class="pt-3">
                                    <button type="submit" class="btn btn-primary">
                                        Update Checklist
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
         
        <livewire:layout.footer />
    </div>
</div>