<?php

namespace App\Jobs;

use App\Models\News;
use App\Models\Post;
use App\Models\User;
use App\Notifications\SendNewsToUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchNewsAndDispatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $topic = array_rand(config('settings.topics'));
        $news = News::where('topic', $topic)->whereBetween('created_at', [now()->subMinutes(5), now()])->take(4)->get();
        if(count($news) > 0){
            foreach ($news as $new){
                Post::create([
                    'title' => $new->title,
                    'body' => $new->body,
                    'slug' => $new->slug . '-' . \Illuminate\Support\Str::random(5),
                    'topic' => $new->topic,
                ]);
            }
            //send Email to all users
            \Notification::send(User::all(), new SendNewsToUsers($news));
        }
    }
}
