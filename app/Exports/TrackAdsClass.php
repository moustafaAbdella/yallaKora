<?php

namespace App\Exports;

use App\Models\TrackAds;
use Maatwebsite\Excel\Concerns\FromCollection;

class TrackAdsClass implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return TrackAds::all();
        return TrackAds::limit(10000)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Track Ad ID',
            'Provider',
            'Ad Type',
            'Ad Shown',
            'App Info',
            'IP Info',
            'User Agent',
            'Browser',
            'Device',
            'Operating System',
            'Shown At',
            'Created At',
            'Updated At',
            'Ad Info',
        ];
    }


    public function map($detail): array
    {
        return [
            $detail->id,
            $detail->track_ad_id,
            $detail->provider,
            $detail->ad_type,
            $detail->ad_shown,
            $detail->app_info,
            $detail->ip_info,
            $detail->user_agent,
            $detail->browser,
            $detail->device,
            $detail->operating_system,
            $detail->shown_at,
            $detail->created_at,
            $detail->updated_at,
            $detail->ad_info,
        ];
    }
}
