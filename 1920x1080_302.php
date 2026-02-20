<?php

declare(strict_types=1);

require_once 'BingImageFetcher.php';

$fetcher = new BingImageFetcher();
$fetcher->serveRedirect('1920x1080');
