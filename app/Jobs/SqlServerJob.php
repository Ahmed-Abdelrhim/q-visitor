<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SqlServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $visit_id;
    public $visitor_name;
    public $date_from;
    public $date_to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($visit_id , $visitor_name , $date_from , $date_to)
    {
        $this->visit_id = $visit_id;
        $this->visitor_name = $visitor_name;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //        DB::connection('sqlsrv')
        //            ->statement("INSERT INTO visits  ( visit_id, visitor_name , date_from , date_to )
        //                                    VALUES ( ". $this->visit_id . " , '" . $this->visitor_name . "'  , '". $this->date_from ."' , '". $this->date_to. "' ); ");


        DB::connection('sqlsrv')
            ->statement("INSERT INTO visits  ( visit_id , visitor_name , date_from , date_to ) 
                                    VALUES ( ". $this->visit_id . "  , '".$this->visit_name."' , '" . $this->date_from ."' , '" . $this->date_to. "' ) ") ;


    }
}
