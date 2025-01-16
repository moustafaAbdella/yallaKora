<?php

namespace App\Scrape;

class ScrapeJdwel {

    protected $url;
    protected $apiKey;
    /**
     * Initializes the ScrapeJdwel.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = "https://jdwalapp.com/";
        $this->apiKey = 'RcO9UZdJpKIs#agTmoAQh$uV8qmRK1^8';
    }

    /**
     * return domain.
     *
     * @return void
     */
    public function getDomainUrl(){
        return $this->url;
    }

     /**
     * return Matches Today.
     *
     * @return void
     */
    public function getMatcheToday(){
        $url_main = $this->url . "today";
        return $this->getScrapeMatches($url_main);
    }

    /**
     * return Matches Today.
     *
     * @return void
     */
    public function getTimeMatche($matchid){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . "match/?id=" .$matchid);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt');
        
        $html = curl_exec($ch);
        

        preg_match_all('!<span class="the_otime">(.*?)<\/span>!', $html, $match);

        $json = [
            'response' => $match[1][0],
            'state' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];

        curl_close($ch);
        return $json;  
    }

    /**
     * return Matches by date.
     *
     * @return void
     */
    public function getMatcheByDate($date){
        $url_main = $this->url . '/matches/?date=' . $date;
        return $this->getScrapeMatches($url_main);
    }

    /**
     * return Matches by date.
     *
     * @return void
     */
    public function getScrapeMatches($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt');

        $html = curl_exec($ch);
        
        
        // echo $html;
        
        $compmatches_list = array();
        //movie name
        preg_match_all('!<ul class="compmatches_list matches_list" data-comp_id="(.*?)">(.*?)<\/div><\/li><\/ul>!', $html, $match);
        $compmatches_list['code'] = $match[2];
        $compmatches_list['id'] = $match[1];
        
        $get_timezone = array();
        preg_match_all("!var matchtime = moment.tz\(match_datetime, '(.*?)'\);!", $html, $timezone);
        $get_timezone['timezone'] = $timezone[1];
        
        $numpage = array();
        $mynump = [];
        $myArray = [];
        
        for ($row = 0; $row < count($compmatches_list['code']); $row++) {
            if( preg_match('!<div class="comp_separator container"><div class="main row py-2 px-1 border_right" style="border-color:.*?"> <img src="(.*?)" class="comp_logo comp_logo_square"\/> <a href="(.*?)" title=".*?"><h4 class="title">(.*?)<\/h4>!', $compmatches_list['code'][$row])) {
                preg_match_all('!<div class="comp_separator container"><div class="main row py-2 px-1 border_right" style="border-color:.*?"> <img src="(.*?)" class="comp_logo comp_logo_square"\/> <a href="(.*?)" title=".*?"><h4 class="title">(.*?)<\/h4>!', $compmatches_list['code'][$row], $match1);
                $name = isset($match1[3][0]) ?$match1[3][0]: "";
                $logo = isset($match1[1][0]) ?$match1[1][0]: "";
                $link = isset($match1[2][0]) ?$match1[2][0]: "";
            }else {
                preg_match_all('!<div class="comp_separator container"><div class="main row py-2 px-1 border_right" style="border-color:.*?"> <img src="(.*?)" class="comp_logo comp_logo_square"\/><h4 class="title">(.*?)<\/h4>!', $compmatches_list['code'][$row], $match1);
                $name = isset($match1[2][0]) ?$match1[2][0]: "";
                $logo = isset($match1[1][0]) ?$match1[1][0]: "";
                $link = "";    
            }
        
            $myMatche = array();
            $myMatches = [];
            preg_match_all('!<li id="(.*?)" class="single_match row.*?" data-keys="(.*?)" data-view_status="(.*?)" onclick=".*?"><div class="team hometeam cell col-5">(.*?)<\/div><\/li>!', $compmatches_list['code'][$row] . '</div></li>', $match2);
            $myMatche['id'] = $match2[1];
            $myMatche['data_keys'] = $match2[2];
            $myMatche['status'] = $match2[3];
            $myMatche['code'] = $match2[4];
        
            for ($rowM = 0; $rowM < count($myMatche['id']); $rowM++) {
                //$team = array();
                preg_match_all('!<img src="(.*?)" class="team_logo" width=".*?" height=".*?" alt=".*?"\/> <span class="the_team">(.*?)<\/span><\/div><div class="middle_column cell col-2"> <span class="match_score".*?> <span class="hometeam">(.*?)<\/span> : <span class="awayteam">(.*?)<\/span>.*?<div class="match_time".*?> <span class="the_otime">(.*?)<\/span> <span class="the_time badge badge-primary badge_fix px-2"><\/span><\/div><\/div><div class="team awayteam cell col-5"> <img src="(.*?)" class="team_logo" width=".*?" height=".*?" alt=".*?"\/> <span class="the_team">(.*?)<\/span><\/div><div class="match_status".*?> <span>(.*?)<\/span>!', $myMatche['code'][$rowM] . '</div></li>', $match3);
                $team_logo_a = isset($match3[1][0]) ?$match3[1][0]: "";
                $team_name_a = isset($match3[2][0]) ?$match3[2][0]: "";
                $team_re_a = isset($match3[3][0]) ?$match3[3][0]: "";
                $team_re_b = isset($match3[4][0]) ?$match3[4][0]: "";
                $time_match = isset($match3[5][0]) ?$match3[5][0]: "";
                $team_logo_b = isset($match3[6][0]) ?$match3[6][0]: "";
                $team_name_b = isset($match3[7][0]) ?$match3[7][0]: "";
                $match_status = isset($match3[8][0]) ?$match3[8][0]: "";
                if(isset($myMatche['status'][$rowM])) {
                    $status = $myMatche['status'][$rowM];
                    if ($myMatche['status'][$rowM] == "live" && preg_match('!<span dir="ltr">(.*?)<\/span>!', $match_status . '</span>') ) {
                        preg_match_all('!<span dir="ltr">(.*?)<\/span>!', $match_status . '</span>',$getStatus);
                        $match_status = $getStatus[1][0];
                    }
                } else{
                    $status ='';
                }
                array_push($myMatches, (object)[
                    'id' => str_replace("match_","",isset($myMatche['id'][$rowM]) ? $myMatche['id'][$rowM]: ""),
                    'data_keys' => isset($myMatche['data_keys'][$rowM]) ? $myMatche['data_keys'][$rowM]: "",
                    'status' => $status,
                    'team_logo_a' => $team_logo_a,
                    'team_name_a' => $team_name_a, 
                    'team_re_a' => $team_re_a, 
                    'team_re_b' => $team_re_b, 
                    'time_match' => $time_match, 
                    'team_logo_b' => $team_logo_b, 
                    'code' => $myMatche['code'][$rowM], 
                    'team_name_b' => $team_name_b,
                    'match_status' => $match_status,        
                ]);
            }
        
            array_push($myArray, (object)[
                'id' => $compmatches_list['id'][$row],
                'name' => $name,
                'logo' => $logo,
                'timezon' => $get_timezone['timezone'][0],
                'matche' => $myMatches,
                'link' => $link,        
            ]);
            //echo $movies['id'][$row] . "<br>" . $movies['name'][$row] . "<br>" . $movies['img'][$row] . "<br>" ;
        
        }
            

        $json = [
            'response' => $myArray,
            'state' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];

        curl_close($ch);
        return $json;  
    } 

    /**
     * return competitions.
     *
     * @return void
     */
    public function competitions() {
        $url = $this->url . 'wp-json/jmanager/web/v1/competitions';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt');

        $json = [
            'response' => json_decode(curl_exec($ch), true),
            'state' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];

        curl_close($ch);
        return $json;   
    }    

    /**
     * return response frome api.
     *
     * @return void
     */
    public function getResponseApi($q) {
        $url = $this->url . $q ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accept: application/json, text/plain, */*',
            'jmtoken: ' . $this->apiKey,
        ));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "okhttp/4.9.2");
        // curl_setopt($ch, CURLOPT_PROXY, '178.209.51.218');
        // curl_setopt($ch, CURLOPT_PROXYPORT, '7829');
        curl_setopt($ch, CURLOPT_PROXYPORT, 443);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt');

        $json = [
            'ur' => $url,
            'response' => json_decode(curl_exec($ch), true),
            'state' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];

        curl_close($ch);

        return $json;  

    }    

}