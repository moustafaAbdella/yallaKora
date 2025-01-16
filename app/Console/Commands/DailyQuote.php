<?php
namespace App\Console\Commands;
use App\Notification\NotificationTe;
use App\Scrape\Scores365\Scrape365;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Scrape\ScrapeJdwel;
use App\Models\User;
use Carbon\Carbon;

class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matche:daily {date?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all matches scraping for day';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Scrape365 $scrape365)
    {
        if($this->argument('date') != null){
            $formatedDate = $this->argument('date');
        }else{
            $date = Carbon::now();
            $formatedDate = $date->format('d/m/Y');
        }
        // $this->info( $formatedDate);

        $matchToday = $scrape365->getMatches(startDate: $formatedDate,endDate: $formatedDate,isLive: false);
        if($matchToday->status == 200){
            $chunckedArray=array_chunk($matchToday->body->games,50);

            foreach ($chunckedArray as $array){
                dispatch(new \App\Jobs\MatchesToday($array,$matchToday->body->countries))->onQueue('matchesToday');
            }
        }

    }
}
