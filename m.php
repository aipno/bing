<?php

declare(strict_types=1);

require_once 'BingImageFetcher.php';

$fetcher = new BingImageFetcher();
$fetcher->serveImage('1080x1920');
