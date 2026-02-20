# Bing Wallpaper Copyright API

## 简介
这是一个扩展的API接口，可以获取Bing每日壁纸的详细版权信息，支持按需返回特定字段。

## API端点
```
GET https://bing.api.iswxl.cn/copyright.php
```

## 请求参数

### type (可选)
指定要返回的特定信息类型：
- `copyright` - 版权信息
- `copyrightlink` - 版权链接
- `title` - 图片标题
- `quiz` - 测验链接
- `enddate` - 结束日期
- `full_image_url` - 完整图片URL

### format (可选)
响应格式：
- `json` - JSON格式（默认）
- `jsonp` - JSONP格式（用于跨域请求）

### callback (可选)
当format=jsonp时使用的回调函数名

## 使用示例

### 1. 获取所有版权信息
```bash
GET https://bing.api.iswxl.cn/copyright.php
```

**响应示例：**
```json
{
    "copyright": "© John Foxx/Getty Images",
    "copyrightlink": "http://www.bing.com/search?q=...",
    "title": "今日图片标题",
    "quiz": "/search?q=Bing+homepage+quiz...",
    "enddate": "20241220",
    "full_image_url": "https://cn.bing.com/th?id=OHR.XXXXX_1920x1080.jpg",
    "timestamp": 1703068800
}
```

### 2. 只获取版权信息
```bash
GET https://bing.api.iswxl.cn/copyright.php?type=copyright
```

**响应示例：**
```json
{
    "type": "copyright",
    "data": "© John Foxx/Getty Images",
    "timestamp": 1703068800
}
```

### 3. 只获取图片URL
```bash
GET https://bing.api.iswxl.cn/copyright.php?type=full_image_url
```

**响应示例：**
```json
{
    "type": "full_image_url",
    "data": "https://cn.bing.com/th?id=OHR.XXXXX_1920x1080.jpg",
    "timestamp": 1703068800
}
```

### 4. JSONP格式（跨域使用）
```bash
GET https://bing.api.iswxl.cn/copyright.php?format=jsonp&callback=myCallback&type=title
```

**响应示例：**
```javascript
myCallback({
    "type": "title",
    "data": "今日图片标题",
    "timestamp": 1703068800
});
```

## 错误响应

### 参数错误
```json
{
    "error": true,
    "message": "不支持的type参数，支持的类型：copyright, copyrightlink, title, quiz, enddate, full_image_url"
}
```

### API请求失败
```json
{
    "error": true,
    "message": "无法获取Bing数据"
}
```

## 支持的客户端示例

### JavaScript (Fetch API)
```javascript
// 获取所有信息
fetch('https://bing.api.iswxl.cn/copyright.php')
    .then(response => response.json())
    .then(data => console.log(data));

// 只获取版权信息
fetch('https://bing.api.iswxl.cn/copyright.php?type=copyright')
    .then(response => response.json())
    .then(data => console.log(data.data));

// JSONP方式（跨域）
function handleCopyright(data) {
    console.log(data.data);
}
const script = document.createElement('script');
script.src = 'https://bing.api.iswxl.cn/copyright.php?format=jsonp&callback=handleCopyright&type=copyright';
document.head.appendChild(script);
```

### Python
```python
import requests

# 获取所有信息
response = requests.get('https://bing.api.iswxl.cn/copyright.php')
data = response.json()
print(data)

# 只获取版权信息
response = requests.get('https://bing.api.iswxl.cn/copyright.php?type=copyright')
data = response.json()
print(data['data'])
```

### cURL
```bash
# 获取所有信息
curl "https://bing.api.iswxl.cn/copyright.php"

# 只获取版权信息
curl "https://bing.api.iswxl.cn/copyright.php?type=copyright"

# JSONP格式
curl "https://bing.api.iswxl.cn/copyright.php?format=jsonp&callback=myFunc&type=title"
```

## 注意事项

1. **缓存建议**：建议客户端实现缓存机制，避免频繁请求
2. **错误处理**：请始终检查响应中的error字段
3. **频率限制**：避免过于频繁的请求，建议间隔至少1小时
4. **HTTPS**：生产环境中建议使用HTTPS

## 更新日志

### v1.0.0
- 初始版本发布
- 支持按type参数返回特定字段
- 支持JSON和JSONP格式
- 添加错误处理机制