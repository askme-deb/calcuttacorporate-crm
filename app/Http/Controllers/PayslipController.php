<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use PDF;
use Illuminate\Support\Facades\Mail;

class PayslipController extends Controller
{


    public function download($id)
    {
        $payroll = Payroll::with('employee', 'items')
            ->findOrFail($id);

        $pdf = \PDF::loadView('payslips.template', compact('payroll'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isJavascriptEnabled' => false,
                'enablePhp' => false,
                'defaultFont' => 'DejaVu Sans',
                'enableFontSubsetting' => true,
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'a4',
                'chroot' => public_path(),
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
            ]);

        return $pdf->download('payslip-' . $payroll->employee->full_name . '.pdf');
    }


    public function preview($id)
    {
        $payroll = Payroll::with('employee', 'items')->findOrFail($id);
        return view('payslips.template', compact('payroll'));
    }

    public function email($id)
    {
        $payroll = Payroll::with('employee.user')->findOrFail($id);
               $pdf = \PDF::loadView('payslips.template', compact('payroll'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isJavascriptEnabled' => false,
                'enablePhp' => false,
                'defaultFont' => 'DejaVu Sans',
                'enableFontSubsetting' => true,
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'a4',
                'chroot' => public_path(),
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
            ]);
        Mail::send([], [], function ($message) use ($pdf, $payroll) {
            $message->to($payroll->employee->user->email)
                ->subject('Your Monthly Payslip')
                ->attachData($pdf->output(), "Payslip.pdf");
        });

        return back()->with('message', 'Payslip emailed successfully!');
    }
}
