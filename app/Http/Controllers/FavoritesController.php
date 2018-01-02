<?php

namespace App\Http\Controllers;

use App\Reply;

/**
 * Class FavoritesController
 * @package App\Http\Controllers
 */
class FavoritesController extends Controller
{

    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }

}
