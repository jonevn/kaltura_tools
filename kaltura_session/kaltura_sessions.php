<?php

require "../../kaltura_client_libs/php5/KalturaClient.php";

define("API_URL", "https://api.kaltura.nordu.net");
define("ADMIN_SECRET", "9d3689f9590c0be91ec4ef53ccdb34ac");
define("USER_ID", "llp@nordu.net");
define("PARTNER_ID", "281");

define("ND_API_URL", "https://api.kaltura.nordu.net");
define("ND_ADMIN_SECRET", "f94773fed47205c6c4d298f4a39b88d7");
define("ND_USER_ID", "llp@nordu.net");
define("ND_PARTNER_ID", "295");

$conf = new KalturaConfiguration();
$conf->serviceUrl = API_URL;
$client = new KalturaClient($conf);
$ks = $client->session->start(ADMIN_SECRET, USER_ID, KalturaSessionType::ADMIN, PARTNER_ID);

$ndconf = new KalturaConfiguration();
$ndconf->serviceUrl = ND_API_URL;
$ndclient = new KalturaClient($ndconf);
$ndks = $ndclient->session->start(ND_ADMIN_SECRET, ND_USER_ID, KalturaSessionType::ADMIN, ND_PARTNER_ID);

printf("Kaltura Session identifiers secret data!\n");
printf("%d %s\n", PARTNER_ID, $ks);
printf("%d %s\n", ND_PARTNER_ID, $ndks);
