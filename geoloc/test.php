<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 21/05/2017
 * Time: 17:16
 */

include("geoipcity.inc");
include("geoipregionvars.php");

$gi = geoip_open(realpath("GeoLiteCity.dat"),GEOIP_STANDARD);

$record = geoip_record_by_addr($gi,'82.247.175.208');

echo $record->country_name . "\n";
echo $GEOIP_REGION_NAME[$record->country_code][$record->region] . "\n";
echo $record->city . "\n";
echo $record->postal_code . "\n";
echo $record->latitude . "\n";
echo $record->longitude . "\n";

geoip_close($gi);

?>
