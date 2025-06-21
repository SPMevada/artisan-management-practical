<?php

namespace App\Jobs;

use App\Mail\AnnouncementEmailNotification;
use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailNotification implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $type;
    protected $annoucement;
    protected $user_id;
    
    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $type, $annoucement)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->annoucement = $annoucement;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user_id = $this->user_id;
        $type = $this->type;
        $annoucement = $this->annoucement;

        $elementQuery = Student::where('user_id', $user_id);

        if($type == 'students') {
            $elements = $elementQuery;
        } else if($type == 'parents' || $type == 'all') {
            $elements = $elementQuery->whereHas('parent',function ($q) use ($user_id) {
                                              $q->where('user_id', $user_id);
                                            });
        }

        $elements->chunk(100, function ($details) use ($type, $annoucement) {
                    foreach ($details as $detail) {
                        if(!empty($detail)) {
                            $student_name = $detail->name;
                            $student_email = $detail->email;
                            if(!empty($detail->parent) && ($type == 'parents' || $type == 'all')) {
                                foreach($detail->parent as $parent) {
                                    $parent_name = $detail->name;
                                    $parent_email = $detail->email;
                                    Mail::to($parent_email)->send(new AnnouncementEmailNotification($annoucement,$parent_name));
                                }
                            }
                            Mail::to($student_email)->send(new AnnouncementEmailNotification($annoucement,$student_name));
                        }
                    }
                });
    }
}
