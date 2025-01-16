<?php

namespace App\Http\Controllers;

use App\Models\Matche;
use App\Models\User;
use App\Scrape\Scores365\Scrape365;
use App\Scrape\ScrapeJdwel;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // navigation routes for the admin panel

    public function index()
    {
        return view('admin.index');
    }

    public function teams()
    {
        return view('admin.teams');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function leagues()
    {
        return view('admin.leagues');
    }

    public function seasons()
    {
        return view('admin.seasons');
    }

    public function matches()
    {
        return view('admin.matches');
    }

    public function notifications()
    {
        return view('admin.notifications');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function subscriptions()
    {
        return view('admin.subscriptions');
    }

    public function livetv()
    {
        return view('admin.livetv');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function settingsPlayer()
    {
        return view('admin.settings-player');
    }

    public function adTrack()
    {
        return view('admin.ad-track');
    }

    public function categories()
    {
        return view('admin.categories');
    }

    public function servers()
    {
        return view('admin.servers');
    }

    public function analysis()
    {
        $date = Carbon::now();
        $fdate = $date->format('Y-m-d');
        $formatedDate = $date->format('Y-m-d H:i:s');
        $getUsers = User::count();
//        $getMatchesComing = Matche::whereDate('start_time','=', $fdate)->where('view_status', '=' , 'coming')->count();
//        {ended:4,started:3,scheduled:2,anticipated:1}
//        return response()->json(
//            [
//                'todaycount' => Matche::whereDate('startTime','=', $fdate)->count(),
//                'comingcount' => Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' , 2)->where('statusText', '=' , 'Scheduled')->count(),
//                'livecount' => Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' , 3)->count()
//            ], 200);
        $getMatches = Matche::whereDate('startTime','=', $fdate)->count();
        $getMatchesDone = Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' , 4)->count();
        $getMatchesLive = Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' , 3)->count();
        $getMatchesComing = Matche::whereDate('startTime','=', $fdate)->where('statusText', '=' , 'Scheduled')->count();
        $lastMatchesLive = Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' ,3)->get();

        return response()->json([
            'countUsers' => $getUsers,
            'countMatches' => $getMatches,
            'countMatchesDone' => $getMatchesDone,
            'countMatchesLive' => $getMatchesLive,
            'countMatchesComing' => $getMatchesComing,
            'lastMatchesComing' => $lastMatchesLive,
        ], 200);
    }
    public function test()
    {
//        $scrape365 = new Scrape365();
//        return $scrape365->getTeams()->competitors[0];

        $scrape365 = new Scrape365();

//        $date = Carbon::now();
//        $formatedDate = $date->format('d-m-Y');
//        $matchToday = $scrape365->getMatches(startDate: $formatedDate,endDate: $formatedDate,isLive: false);
//
////        {ended:4,started:3,scheduled:2,anticipated:1}
//        return $matchToday->body;
//         $match = $scrape365->getMatchDetails('4183285');
//        $schedule_date = Carbon::parse($match->body->game->startTime)->setTimezone('Asia/Riyadh');
//        $triggerOn = $schedule_date->format('Y-m-d H:i:s');
//        return [
//            'match' => $match->body->game,
//            'triggerOn' => $triggerOn,
//            'triggerOnEgypt' => $schedule_date->setTimezone('Africa/Cairo')->format('Y-m-d H:i:s'),
//            'match-db' => Matche::where('api_id', $match->body->game->id)->first(),
//        ];
//        return $match;
//       return $scrape365->getLeague(11);
//        return $scrape365->getTeams();
          $team = $scrape365->getTeam(67945);
          $team =  $team->body->competitors[0];
          $filename = $team->id . '.png';
        if(!Storage::disk('teams')->exists($filename)){
            try {
                $image_client = new Client();
                $image = $image_client->request('GET', $scrape365->getDomainImageUrl() . '/image/upload/f_png,c_limit,q_auto:eco,dpr_2,d_Competitors:default1.png/v'.$team->imageVersion.'/Competitors/' . $team->id, [
                    'verify' => false
                ]);
                if ($image->getStatusCode() == 200) {
                    $result = Storage::disk('teams')->put($filename, $image->getBody()->getContents());
                } else {
                    throw new \Exception('Failed to fetch image: ' . $image->getStatusCode());
                }

                if ($result) {
//                    Log::info('Image stored successfully: ' . $filename);
                } else {
                    throw new \Exception('Failed to store image: ' . $filename);
                }
                $filename = $team->id . '.png';
            } catch (\Exception $exception) {
//                $this->command->error( 'in ' . $team->id . ':'. $team->name  . ' '.$exception->getMessage());
                return $exception;
                $filename = null;
            }
        }else {
            return 'aaaa' . $filename;
        }
        return ['file' => $filename , 'a' => $scrape365->getDomainImageUrl() . '/image/upload/f_png,c_limit,q_auto:eco,dpr_2,d_Competitors:default1.png/v'.$team->imageVersion.'/Competitors/' . $team->id];
//        $scrape365->setLangId(27);
//        return $scrape365->getLeagues()->competitions[0];

        $scrape365->setLangId(27);
        $date = Carbon::now();
        $formatedDate = $date->format('d-m-Y');
//        return $formatedDate;
        return $scrape365->getMatches(startDate: $formatedDate,endDate: $formatedDate,isLive: false);

        $scrapeJdwel = new ScrapeJdwel();

        $date = Carbon::now();
        $formatedDate = $date->format('Y-m-d');

        // $this->info( $formatedDate);

        $matchToday = $scrapeJdwel->getResponseApi('wp-json/jmanager/v1/matchesBy/' . $formatedDate);
        return $matchToday;
//         $ss = Carbon::createFromFormat('Y-m-d H:i:s', '2022-11-01 23:00:00')
//     ->settings(['timezone' => 'Asia/Riyadh']);
//     //$date = new DateTime("now", new DateTimeZone('Asia/Riyadh') );
//     //$date = $this->formatDateTimeZone('2022-11-01 23:00:00','long','Asia/Riyadh','Africa/Cairo');
//    $date = Carbon::parse('2022-11-01 23:00:00', 'Asia/Riyadh')->setTimezone('Asia/Amman');
    //     $sendNoti = new NotificationFb;
    //     $ss = $sendNoti->sendMsgToFavourite('بداية المباراة!','اختبار',1,2,4,"alarm.caf");
    // dd($ss );
    //dd(date_create(null, timezone_open('Asia/Amman')));
    //     $date = Carbon::now();
    //     $fdate = Carbon::now()->subDay(1)->format('Y-m-d');
    //     $formatedDate  = $date->format('Y-m-d H:i:s');
    //     $getMatches    = Matche::where('start_time','>', $fdate)->where('start_time','<', $formatedDate)->where('view_status', '!=' , 'done')->get();
    //     // $sendChat = new NotificationTe;
    //     //         $sendChat->sendMsgToAdmin('MatchesRefresh');

    //     if($getMatches != null) {

    //         dispatch(new \App\Jobs\MatchesRefresh($getMatches))->onQueue('matchesRefresh');

    //     }
    //    // FCM response
    //    return $getMatches;
       // if($matchToday->state ==)
    }

    /**
    * Return the formated date based on timezone selected from sidebar nav | Front-end user | Saved in Cookie | Sent $to
    *
    * @param Carbon $date
    * @param null $type (long | short)
    * @return Carbon date
    */
    public function formatDateTimeZone($date, $type = 'long', $from = null, $to = null)
    {
        if (!$from)
            $from = 'UTC';

        if (!$to)
            $to = Cookie::get('timezone_user');

        $dateUTC = Carbon::now();
        $datePST = Carbon::now();
        $datePST->setTimezone($to);

        $start  = new Carbon($dateUTC);
        $end    = new Carbon($datePST);
        $difference = $start->diffInHours($end, false); // set false to see the negative difference

        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
        $carbon->addHours($difference);

        if ($type == 'short')
        {
            $carbon = Carbon::parse($carbon)->format('Y-m-d');
        }

        return $carbon;
    }

}
