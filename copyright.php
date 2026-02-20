<?php
// 获取请求参数
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : 'json';
$callback = isset($_GET['callback']) ? trim($_GET['callback']) : '';

// 设置默认响应格式
header('Content-Type: application/json; charset=utf-8');

// Bing API URL
$url = "https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=zh-CN";

// 初始化cURL
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);

$headers = array(
    "Accept: application/json",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// SSL设置
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// 执行请求
$resp = curl_exec($curl);
// 使用curl_reset替代curl_close，更符合现代PHP实践
curl_reset($curl);
unset($curl);

// 检查请求是否成功
if (!$resp) {
    $error_response = array(
        'error' => true,
        'message' => '无法获取Bing数据'
    );
    
    if ($format === 'jsonp' && !empty($callback)) {
        header('Content-Type: application/javascript; charset=utf-8');
        echo $callback . '(' . json_encode($error_response, JSON_UNESCAPED_UNICODE) . ');';
    } else {
        echo json_encode($error_response, JSON_UNESCAPED_UNICODE);
    }
    exit();
}

// 解析JSON数据
$array = json_decode($resp);

// 检查数据是否有效
if (!isset($array->images) || !is_array($array->images) || count($array->images) == 0) {
    $error_response = array(
        'error' => true,
        'message' => 'Bing API返回数据格式错误'
    );
    
    if ($format === 'jsonp' && !empty($callback)) {
        header('Content-Type: application/javascript; charset=utf-8');
        echo $callback . '(' . json_encode($error_response, JSON_UNESCAPED_UNICODE) . ');';
    } else {
        echo json_encode($error_response, JSON_UNESCAPED_UNICODE);
    }
    exit();
}

// 获取第一张图片的信息
$image = $array->images[0];

// 构建完整图片URL
$imgurl = 'https://cn.bing.com' . $image->urlbase . '_1920x1080.jpg';

// 提取版权相关信息
$copyright_data = array(
    'copyright' => isset($image->copyright) ? $image->copyright : '',
    'copyrightlink' => isset($image->copyrightlink) ? $image->copyrightlink : '',
    'title' => isset($image->title) ? $image->title : '',
    'quiz' => isset($image->quiz) ? $image->quiz : '',
    'enddate' => isset($image->enddate) ? $image->enddate : '',
    'full_image_url' => $imgurl,
    'timestamp' => time()
);

// 根据type参数返回相应数据
if (!empty($type)) {
    $type = strtolower($type);
    
    // 支持的字段类型
    $supported_types = array('copyright', 'copyrightlink', 'title', 'quiz', 'enddate', 'full_image_url');
    
    if (in_array($type, $supported_types)) {
        $response = array(
            'type' => $type,
            'data' => $copyright_data[$type],
            'timestamp' => $copyright_data['timestamp']
        );
    } else {
        $response = array(
            'error' => true,
            'message' => '不支持的type参数，支持的类型：' . implode(', ', $supported_types)
        );
    }
} else {
    // 返回所有信息
    $response = $copyright_data;
}

// 格式化输出
if ($format === 'jsonp' && !empty($callback)) {
    header('Content-Type: application/javascript; charset=utf-8');
    echo $callback . '(' . json_encode($response, JSON_UNESCAPED_UNICODE) . ');';
} else {
    // 默认JSON格式
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

exit();
?>