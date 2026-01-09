<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function build()
    {
        $pdf = Pdf::loadView('sales.pdf', ['sale' => $this->sale]);

        return $this->subject('Remisión de Venta #' . $this->sale->folio)
                    ->view('emails.sale_ticket') 
                    ->attachData($pdf->output(), "Remision_{$this->sale->folio}.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}