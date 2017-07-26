<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Clipping;
use App\Note;

class ParseClippingToNote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userID;
    private $clippingFilePath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userID, $clippingFilePath)
    {
        $this->userID = $userID;
        $this->clippingFilePath = $clippingFilePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clipping = new Clipping($this->clippingFilePath);
        if ($clipping->parse()){
            unlink($this->clippingFilePath);
            $notes = $clipping->getNotes();
            foreach ($notes as $index=>$note){
                $noteModel = new Note;
                $noteModel->user_id = $this->userID;
                $noteModel->title = $note['title'];
                $noteModel->author = $note['author'];
                $noteModel->dateTime = $note['dateTime'];
                $noteModel->text = $note['text'];
                $noteModel->save();
            }
        }

    }
}
