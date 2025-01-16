<?php

namespace App\Helpers;

use IP2Location\Database;
use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;

// This creates the Reader object, which should be reused across
// lookups.
class IpLocationHelper
{
    protected $db;
    protected $db2;
    protected $proxy;

    /**
     * @throws InvalidDatabaseException
     * @throws \Exception
     */
    public function __construct()
    {
        // تأكد من مسار ملف BIN
        $this->db    = new Database(storage_path('ipDataFiles/IP2LOCATION/IP2LOCATION-LITE-DB11.BIN'), Database::FILE_IO);
//        $this->db2   = new Reader(storage_path('ipDataFiles/DB_IP/dbip-asn-lite-2024-06.mmdb'));
        $this->proxy = new Database(storage_path('ipDataFiles/IP2LOCATION/IP2PROXY-LITE-PX11.BIN'), Database::FILE_IO);
 
    
    }

    public function getLocation($ip)
    {
        $proxy  = (object) $this->proxy->lookup($ip, Database::ALL);
        $ipData = (object) [];
        if ($proxy->regionName == "-") {
            $IP2LOCATION         = (object) $this->db->lookup($ip, Database::ALL);
            $ipData->ipAddress   = $IP2LOCATION->ipAddress;
//            $ipData->asn         = $this->db2->asn($ip)->autonomousSystemOrganization;
            $ipData->latitude    = $IP2LOCATION->latitude;
            $ipData->longitude   = $IP2LOCATION->longitude;
            $ipData->countryName = $IP2LOCATION->countryName;
            $ipData->countryCode = $IP2LOCATION->countryCode;
            $ipData->timeZone    = $IP2LOCATION->timeZone;
            $ipData->cityName    = $IP2LOCATION->cityName;
            $ipData->regionName  = $IP2LOCATION->regionName;
            $ipData->isProxy     = false;

        } else {
            $IP2LOCATION         = (object) $this->db->lookup($ip, Database::ALL);
            $ipData->ipAddress   = $proxy->ipAddress;
//            $ipData->asn         = $this->db2->asn($ip)->autonomousSystemOrganization;
            $ipData->latitude    = $proxy->latitude;
            $ipData->longitude   = $proxy->longitude;
            $ipData->countryName = $proxy->countryName;
            $ipData->countryCode = $proxy->countryCode;
            $ipData->timeZone    = $proxy->timeZone;
            $ipData->cityName    = $proxy->cityName;
            $ipData->regionName  = $proxy->regionName;
            $ipData->isProxy     = true;
        }

        // return $this->proxy->lookup($ip, Database::ALL);
        return $ipData;
    }
}
