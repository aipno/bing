<?php

declare(strict_types=1);

/**
 * Class BingImageFetcher
 * Handles fetching Bing daily wallpaper information and serving images.
 */
class BingImageFetcher
{
    private const BING_API_URL = "https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=zh-CN";
    private const BING_BASE_URL = "https://cn.bing.com";

    /**
     * Fetches the image data from Bing API.
     *
     * @return object|null The decoded JSON object or null on failure.
     */
    public function fetchImageData(): ?object
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::BING_API_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
            ],
            // In a production environment with valid certificates, these should be true.
            // Keeping them false for compatibility with existing code, but it's not recommended for security.
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            // Log error if needed: curl_error($curl);
            return null;
        }

        $data = json_decode((string)$response);

        if (!is_object($data) || empty($data->images) || !is_array($data->images)) {
            return null;
        }

        return $data;
    }

    /**
     * Gets the full image URL for a specific resolution.
     *
     * @param string $resolution The resolution suffix (e.g., '1920x1080', '1366x768', '1080x1920', 'UHD').
     * @return string|null The full URL or null if data fetch fails.
     */
    public function getImageUrl(string $resolution = '1920x1080'): ?string
    {
        // Normalize 4K/UHD resolution
        if ($resolution === '4k' || $resolution === '3840x2160') {
            $resolution = 'UHD';
        }

        $data = $this->fetchImageData();
        
        if (!$data) {
            return null;
        }

        $urlBase = $data->images[0]->urlbase ?? null;
        
        if (!$urlBase) {
            return null;
        }

        return self::BING_BASE_URL . $urlBase . '_' . $resolution . '.jpg';
    }

    /**
     * Redirects to the image URL.
     *
     * @param string $resolution The resolution suffix.
     */
    public function serveRedirect(string $resolution = '1920x1080'): void
    {
        $this->sendCorsHeaders();
        $url = $this->getImageUrl($resolution);
        
        if ($url) {
            header('Location: ' . $url);
            exit();
        }
        
        $this->serveError();
    }

    /**
     * Serves the image content directly.
     *
     * @param string $resolution The resolution suffix.
     */
    public function serveImage(string $resolution = '1920x1080'): void
    {
        $this->sendCorsHeaders();
        $url = $this->getImageUrl($resolution);
        
        if ($url) {
            header('Content-Type: image/jpeg');
            header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
            
            // Clean output buffer to ensure no whitespace is sent before image
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            readfile($url);
            exit();
        }
        
        $this->serveError();
    }

    /**
     * Serves an error message.
     */
    private function serveError(): void
    {
        $this->sendCorsHeaders();
        http_response_code(500);
        exit('error: Unable to fetch image');
    }

    /**
     * Sends CORS headers.
     */
    private function sendCorsHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }
}
