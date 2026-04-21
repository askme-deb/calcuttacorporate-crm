<?php
namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    public function preview(Proposal $proposal)
    {
        $proposal->load('lead', 'items');
        return view('livewire.proposals.proposal-preview', compact('proposal'));
    }

    public function download(Proposal $proposal)
    {
        $proposal->load('lead', 'items');
        $pdf = PDF::loadView('livewire.proposals.proposal-preview', compact('proposal'));
        return $pdf->download('proposal-'.$proposal->id.'.pdf');
    }

    public function email(Proposal $proposal)
    {
        $proposal->load('lead', 'items');
        $pdf = PDF::loadView('livewire.proposals.proposal-preview', compact('proposal'));
        $lead = $proposal->lead;
        Mail::send('emails.proposal', compact('proposal'), function($message) use ($lead, $pdf, $proposal) {
            $message->to($lead->email)
                ->subject('Your Proposal from Calcutta Corporate')
                ->attachData($pdf->output(), 'proposal-'.$proposal->id.'.pdf');
        });
        return back()->with('success', 'Proposal sent via email.');
    }
}
