<?php

/*
 * This script will use the ND_ settings from config.php to create a
 * list of Playlist ID:s and their respective ReferenceID:s
*/


date_default_timezone_set("UTC");

require "../external/kaltura-api/php5/KalturaClient.php";
require "../config.php";

$xmlEntrySize = 200;
$timeLast = time();


$conf = new KalturaConfiguration();
$conf->serviceUrl = ND_API_URL;
$client = new KalturaClient($conf);
$ks = $client->session->start(ND_ADMIN_SECRET, ND_USER_ID, KalturaSessionType::ADMIN, ND_PARTNER_ID);
$client->setKs($ks);

$filter = new KalturaPlaylistFilter();
$filter->orderBy = KalturaPlaylistOrderBy::CREATED_AT_ASC; 
$pager = new KalturaFilterPager();
$pager->pageSize = 1;
$pager->pageIndex = 1;
$result = $client->playlist->listAction($filter, $pager);
$timeFirst = $result->objects[0]->createdAt;
$timeDelta = time() - $timeFirst;	
$counterEnts = $result->totalCount;
$maxEnts = 10000;

// Figure out if it is possible to fetch all entries in one pagination, or if there is a need to split them.
// Kaltura limits pagination at 300 entries pr page, and 10000 pr pagination.
while ($counterEnts > $maxEnts) {
	$timeDelta = floor($timeDelta / 2);
	$counterEnts = 0;
	for ($timeRange = $timeFirst; ($timeLast + $timeDelta) > $timeRange; $timeRange += $timeDelta) {
		$filter->createdAtGreaterThanOrEqual = $timeRange;
		$filter->createdAtLessThanOrEqual = $timeRange + ($timeDelta - 1);
		$result = $client->playlist->listAction($filter, $pager);
		if ($result->totalCount > $counterEnts)
			$counterEnts = $result->totalCount;
	}
}
echo "timeDelta: {$timeDelta}\n";



if ($timeDelta == 0) {
	$timeDelta = time();
}

$fh_ref = fopen("playlist_ref_to_id.csv", "w");
$fh_id = fopen("playlist_id_to_ref.csv", "w");

$pager->pageSize = 300;

// Get the actual entries
for ($timeRange = $timeFirst; ($timeLast + $timeDelta) > $timeRange; $timeRange += $timeDelta) {
	$filter->createdAtGreaterThanOrEqual = $timeRange;
	$filter->createdAtLessThanOrEqual = $timeRange + ($timeDelta - 1);
	$pager->pageIndex = 1;
	$result = $client->playlist->listAction($filter, $pager);
	while (count($result->objects)) {
		foreach($result->objects as $playlist) {
			if (strlen($playlist->referenceId) > 0) {
				fprintf($fh_ref, "%s,%s,%s\n", $playlist->referenceId, $playlist->id, $playlist->thumbnailUrl);
				fprintf($fh_id, "%s,%s,%s\n", $playlist->id, $playlist->referenceId, $playlist->thumbnailUrl);
			}
		}
		printf(" Fetching %d-%d of %d entries for date range %s - %s.   \r", $pager->pageIndex * $pager->pageSize, ($pager->pageIndex+1) * $pager->pageSize, $result->totalCount, date("c", $timeRange), date("c", $timeRange + ($timeDelta - 1)));
		$pager->pageIndex++;
		$result = $client->playlist->listAction($filter, $pager);
	}
	echo "\n";
}

fclose($fh_ref);
fclose($fh_id);