<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/4
 * Time: 下午7:13
 */

namespace App\Listeners\WxOpenPlatform;


use App\Events\Event;

class AuthorizedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $a = 1;
    }
}