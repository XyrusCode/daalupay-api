<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\BlogPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBlogPost extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BlogPost $post
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Blog Post: '.$this->post->title
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.blog.new_post',
            with: [
                'post' => $this->post,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
