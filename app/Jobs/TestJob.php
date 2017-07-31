<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/8/1
 * Time: 上午2:39
 */

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }


    public function handle()
    {
        //Do something here

        print_r($this->params);
    }

}