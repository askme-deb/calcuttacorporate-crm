<?php

namespace App\Livewire\General;

use App\Models\Event;
use Livewire\Component;

class EventCalendar extends Component
{

  public $events = [];

    // Remove any constructor that requires parameters
    // If you need dependencies, use Laravel's service container
    // and inject them in the mount method instead
    
    public function mount($data = null)
    {
        $this->loadEvents();
        //dd($this->events);
      $this->dispatch('calendar-mounted', events: $this->events);

    }

 public function loadEvents()
    {
        // Get events from database but map field names for FullCalendar
        $dbEvents = Event::select('id', 'title', 'start_date', 'end_date')->get();
        
        // Transform to FullCalendar format
        $this->events = $dbEvents->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date, // Map start_date to start
                'end' => $event->end_date,     // Map end_date to end
            ];
        })->toArray();
    }

    public function addEvent($title, $start)
    {
      //  dd($title);
        // Create a new event in the database
        $event = Event::create([
            'title' => $title,
            'start' => $start,
            // Add more fields as needed
        ]);

        // Reload events and notify the frontend
        $this->loadEvents();
        $this->dispatch('calendar-events-updated', ['events' => $this->events]);
    }

    public function deleteEvent($id)
    {
        // Delete the event from the database
        Event::find($id)->delete();

        // Reload events and notify the frontend
        $this->loadEvents();
        $this->dispatch('calendar-events-updated', ['events' => $this->events]);
    }


    // Add listeners for the dispatched events
    protected function getListeners()
    {
        return [
            'calendar-event-added' => 'handleEventAdded',
            'calendar-event-deleted' => 'handleEventDeleted',
        ];
    }

    public function handleEventAdded($data)
    {
      
        $this->addEvent($data['title'], $data['start']);
    }

    public function handleEventDeleted($data)
    {
        $this->deleteEvent($data['id']);
    }
    public function render()
    {
        return view('livewire.general.event-calendar');
    }
}
