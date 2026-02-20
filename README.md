## 简介

Bing首页每日更新一张来自世界各地的精美图片。通过imgRun提供的API链接可以简单、快速地获取栩栩如生的每日壁纸，每日自动更新，作为网站背景和电脑壁纸都非常不错……

## 致谢

特别感谢 [mike126126](https://github.com/mike126126) 的开源项目 [bing](https://github.com/mike126126/bing)，本项目基于其优秀的工作进行了扩展和改进。

## 新增功能：版权信息API

我们新增了一个强大的版权信息API，可以按需获取Bing每日壁纸的详细信息。

### API端点
```
GET /copyright.php
```

### 支持的参数

#### type (可选)
指定要返回的特定信息类型：
- `copyright` - 版权信息
- `copyrightlink` - 版权链接  
- `title` - 图片标题
- `quiz` - 测验链接
- `enddate` - 结束日期
- `full_image_url` - 完整图片URL

#### format (可选)
响应格式：
- `json` - JSON格式（默认）
- `jsonp` - JSONP格式（用于跨域请求）

#### callback (可选)
当format=jsonp时使用的回调函数名

### 使用示例

#### 获取所有版权信息
```html
<script>
fetch('/copyright.php')
    .then(response => response.json())
    .then(data => console.log(data));
</script>
```

#### 只获取版权信息
```html
<script>
fetch('/copyright.php?type=copyright')
    .then(response => response.json())
    .then(data => console.log(data.data));
</script>
```

#### JSONP跨域请求
```html
<script>
function handleData(data) {
    console.log(data.data);
}
</script>
<script src="/copyright.php?format=jsonp&callback=handleData&type=title"></script>
```

更多详细使用说明请查看 [API_README.md](API_README.md)

## 使用方法

本API接口的链接，可以直接把它当做一个图片url链接来用，插入如下代码：

### PHP直接输出图片

```
<img src="https://bing.api.iswxl.cn/1920x1080.php" alt="Bing每日壁纸1080P高清" />

<img src="https://bing.api.iswxl.cn/1366x768.php" alt="Bing每日图片" />

<img src="https://bing.api.iswxl.cn/m.php" alt="Bing每日壁纸手机超高清" />
```

### 跳转至Bing图片直链

```
<img src="https://bing.api.iswxl.cn/1920x1080_302.php" alt="Bing每日壁纸1080P高清" />

<img src="https://bing.api.iswxl.cn/1366x768_302.php" alt="Bing每日图片" />

<img src="https://bing.api.iswxl.cn/m_302.php" alt="Bing每日壁纸手机超高清" />
```

## 文件说明

在需要引用图片的地方插入url即可。不同参数url说明如下：

[https://bing.api.iswxl.cn/1920×1080.php](https://bing.api.iswxl.cn/1920x1080.php) PHP链接直接输出1920×1080分辨率图片。

[https://bing.api.iswxl.cn/1920x1080_302.php](https://bing.api.iswxl.cn/1920x1080_302.php) 输出为1920×1080分辨率的Bing直链图片。

[https://bing.api.iswxl.cn/1366×768.php](https://bing.api.iswxl.cn/1366x768.php) PHP链接直接输出1366×768分辨率图片。

[https://bing.api.iswxl.cn/1366x768_302.php](https://bing.api.iswxl.cn/1366x768_302.php) 输出为1366×768分辨率的Bing直链图片。

[https://bing.api.iswxl.cn/m.php](https://bing.api.iswxl.cn/m.php) PHP链接直接输出1080×1920分辨率竖版图片。

[https://bing.api.iswxl.cn/m_302.php](https://bing.api.iswxl.cn/m_302.php) 输出为1080×1920分辨率的Bing直链竖版图片。

[https://bing.api.iswxl.cn/copyright.php](https://bing.api.iswxl.cn/copyright.php) 新增的版权信息API接口。

## 版本更新

2022年4月19日，初版1.0.0版本发布。

2026年2月，版本2.0.0发布：
- 新增版权信息API接口
- 支持按需获取特定字段信息
- 支持JSON和JSONP格式
- 改进cURL资源管理，使用现代PHP最佳实践
- 添加详细的API文档和使用示例