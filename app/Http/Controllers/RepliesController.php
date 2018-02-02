<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;

/**
 * Class RepliesController
 * @package App\Http\Controllers
 */
class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @param Spam $spam
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validateReply();
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        if (request()->expectsJson()) {
            return $reply->load('owner');
        }
        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $this->validateReply();
        $reply->update(['body' => request('body')]);

    }

    public function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }
}
