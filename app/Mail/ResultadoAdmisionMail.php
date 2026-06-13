<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;
use App\Models\Postulacion;

class ResultadoAdmisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $postulacion;

    /**
     * Create a new message instance.
     */
    public function __construct(Usuario $usuario, Postulacion $postulacion)
    {
        $this->usuario = $usuario;
        $this->postulacion = $postulacion;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resultados de Admisión - CUP FICCT',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.resultado_admision',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
