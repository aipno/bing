<?php

declare(strict_types=1);

require_once 'BingImageFetcher.php';

// 获取请求参数
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : 'json';
$callback = isset($_GET['callback']) ? trim($_GET['callback']) : '';

// 设置默认响应格式
header('Content-Type: application/json; charset=utf-8');

$fetcher = new BingImageFetcher();
$data = $fetcher->fetchImageData();

if (!$data) {
    sendError('无法获取Bing数据', $format, $callback);
}

// 获取第一张图片的信息
$image = $data->images[0];
$imgUrl = 'https://cn.bing.com' . $image->urlbase . '_1920x1080.jpg';

// 提取版权相关信息
$copyrightData = [
    'copyright' => $image->copyright ?? '',
    'copyrightlink' => $image->copyrightlink ?? '',
    'title' => $image->title ?? '',
    'quiz' => $image->quiz ?? '',
    'enddate' => $image->enddate ?? '',
    'full_image_url' => $imgUrl,
    'hsh' => $image->hsh ?? '',
    'startdate' => $image->startdate ?? '',
    'urlbase' => $image->urlbase ?? '',
    'timestamp' => time()
];

// 根据type参数返回相应数据
if (!empty($type)) {
    $type = strtolower($type);

    // 支持的具体字段类型
    $supportedTypes = [
        'copyright',      // 版权信息
        'copyrightlink',  // 版权链接
        'title',          // 图片标题
        'quiz',           // 测验链接
        'enddate',        // 结束日期
        'startdate',      // 开始日期
        'hsh',            // 图片哈希值
        'urlbase',        // URL基础路径
        'full_image_url', // 完整图片URL
        'image_name'      // 图片文件名（从urlbase提取）
    ];

    if (in_array($type, $supportedTypes, true)) {
        $responseData = '';

        if ($type === 'image_name') {
            // 从urlbase提取图片文件名
            $parts = explode('/', $copyrightData['urlbase']);
            $responseData = end($parts) . '_1920x1080.jpg';
        } elseif (array_key_exists($type, $copyrightData)) {
            $responseData = $copyrightData[$type];
        }

        $response = [
            'type' => $type,
            'data' => $responseData,
            'timestamp' => $copyrightData['timestamp']
        ];
    } else {
        sendError('不支持的type参数，支持的类型：' . implode(', ', $supportedTypes), $format, $callback);
    }
} else {
    // 返回所有信息
    $response = $copyrightData;
}

// 输出响应
sendResponse($response, $format, $callback);

/**
 * 发送错误响应
 */
function sendError(string $message, string $format, string $callback): void
{
    $response = [
        'error' => true,
        'message' => $message
    ];
    sendResponse($response, $format, $callback);
    exit();
}

/**
 * 发送格式化响应
 */
function sendResponse(array $data, string $format, string $callback): void
{
    if ($format === 'jsonp' && !empty($callback)) {
        header('Content-Type: application/javascript; charset=utf-8');
        echo $callback . '(' . json_encode($data, JSON_UNESCAPED_UNICODE) . ');';
    } else {
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
