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
- `enddate` - 结束日期 (YYYYMMDD格式)
- `startdate` - 开始日期 (YYYYMMDD格式)
- `hsh` - 图片哈希值标识符
- `urlbase` - URL基础路径
- `full_image_url` - 完整图片URL
- `image_name` - 图片文件名

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
    "startdate": "20241219",
    "hsh": "abcdef123456789",
    "urlbase": "/th?id=OHR.XXXXX",
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

### 3. 只获取图片哈希值
```bash
GET https://bing.api.iswxl.cn/copyright.php?type=hsh
```

**响应示例：**
```json
{
    "type": "hsh",
    "data": "abcdef123456789",
    "timestamp": 1703068800
}
```

### 4. 只获取图片文件名
```bash
GET https://bing.api.iswxl.cn/copyright.php?type=image_name
```

**响应示例：**
```json
{
    "type": "image_name",
    "data": "OHR.XXXXX_1920x1080.jpg",
    "timestamp": 1703068800
}
```

### 5. JSONP格式（跨域使用）
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
    "message": "不支持的type参数，支持的类型：copyright, copyrightlink, title, quiz, enddate, startdate, hsh, urlbase, full_image_url, image_name"
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

// 只获取图片哈希值
fetch('https://bing.api.iswxl.cn/copyright.php?type=hsh')
    .then(response => response.json())
    .then(data => console.log(data.data));

// 只获取开始日期
fetch('https://bing.api.iswxl.cn/copyright.php?type=startdate')
    .then(response => response.json())
    .then(data => console.log(data.data));

// JSONP方式（跨域）
function handleCopyright(data) {
    console.log(data.data);
}
const script = document.createElement('script');
script.src = 'https://bing.api.iswxl.cn/copyright.php?format=jsonp&callback=handleCopyright&type=image_name';
document.head.appendChild(script);
```

### Python
```python
import requests

# 获取图片文件名
response = requests.get('https://bing.api.iswxl.cn/copyright.php?type=image_name')
data = response.json()
print(data['data'])

# 获取开始日期
response = requests.get('https://bing.api.iswxl.cn/copyright.php?type=startdate')
data = response.json()
print(data['data'])
```

### cURL
```bash
# 获取图片哈希值
curl "https://bing.api.iswxl.cn/copyright.php?type=hsh"

# 获取URL基础路径
curl "https://bing.api.iswxl.cn/copyright.php?type=urlbase"

# 获取结束日期
curl "https://bing.api.iswxl.cn/copyright.php?type=enddate"
```

## 新增参数说明

### startdate
返回图片的开始显示日期，格式为YYYYMMDD

### hsh
返回图片的唯一哈希标识符，可用于图片识别和缓存

### urlbase
返回图片URL的基础路径部分

### image_name
根据urlbase自动提取的图片文件名，格式为"文件名_1920x1080.jpg"

## 注意事项

1. **缓存建议**：建议客户端实现缓存机制，避免频繁请求
2. **错误处理**：请始终检查响应中的error字段
3. **频率限制**：避免过于频繁的请求，建议间隔至少1小时
4. **HTTPS**：生产环境中建议使用HTTPS

## 更新日志

### v1.1.0
- 细化参数类型，新增startdate、hsh、urlbase、image_name参数
- 每个type参数现在只返回一条具体信息
- 优化图片文件名提取逻辑

### v1.0.0
- 初始版本发布
- 支持按type参数返回特定字段
- 支持JSON和JSONP格式
- 添加错误处理机制