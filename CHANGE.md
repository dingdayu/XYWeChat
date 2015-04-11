##更新日志

###v1.0.x

#####v1.0.0 beta

预览版：

 - 基本功能完成；
 - 支持绝大多数微信公众平台功能


#####v1.0.0 releases

发布v1.0.0正式版。

主要更新：

- 新建分支 `mathjax-version`，但不打算继续对此分支进行开发；

- 移除MathJax，改用KaTeX[#2](https://github.com/pandao/editor.md/issues/2)，解析和预览响应速度大幅度提高[#3](https://github.com/pandao/editor.md/issues/3)；
    - 移除 `mathjax` 配置项；
    - 移除 `mathjaxURL` 属性；
    - 移除 `setMathJaxConfig()` 方法；
    - 移除 `loadMathJax()` 方法；
    - 移除MathJax的所有示例；
    - 新增 `tex` 配置项，表示是否开启支持科学公式TeX，基于KaTeX；
    - 新增 `katexURL` 属性；
    - 新增 `loadKaTeX` 方法；
    - 新增KaTeX的示例；
    
- `setCodeEditor()`方法更名为`setCodeMirror()`；

- 合并CodeMirror使用到的多个JS模块文件，大幅减少HTTP请求，加快下载速度；
    - 新增合并后的两个模块文件：`./lib/codemirror/modes.min.js`、`./lib/codemirror/addons.min.js`；
    - `Gulpfile.js` 新增合并CodeMirror模块文件的任务方法`codemirror-mode`和`codemirror-addon`；
    - 另外在使用Require.js时，因为CodeMirror的严格模块依赖的限制，不能使用上述合并的模块文件，仍然采用动态加载多个模块文件；
    
- 更新 `README.md` 等相关文档和示例；

- 解决Sea.js环境下Raphael.js无法运行导致不支持流程图和时序图的问题，即必须先加载Raphael.js，后加载Sea.js；

###v1.1.x

#####v1.1.0

主要更新：

- 设计并更换了Logo；
- 新增添加图片、链接、锚点链接、代码块、预格式文本等操作弹出对话框层及示例；
- 新增支持图片(跨域)上传；
- 改用`<textarea>`来存放Markdown源文档；
- 新增支持自定义工具栏；
- 新增支持多语言；
- 新增支持Zepto.js；
- 新增支持多个Editor.md并存和动态加载Editor.md及示例；
- 新增支持智能识别和解析HTML标签及示例；
- 新增多个外部操作方法接口及示例；
- 修复了一些大大小小的Bug；

具体更新如下：

- 更换Logo，建立基础VI；
    - 创建了全系列WebFont字体`dist/fonts/editormd-logo.*`；
    - 新增样式类`editormd-logo`等；

- 改用`<textarea>`来存放Markdown源文档；
    - 原先使用`<script type="text/markdown"></script>`来存放Markdown源文档；
    - 创建Editor.md只需要写一个`<div id="xxxx"></div>`，如果没有添加`class="editormd"`属性会自动添加，另外如果不存在`<textarea>`标签，则也会自动添加`<textarea>`；

- 新增支持智能识别和解析HTML标签，增强了Markdown语法的扩展性，几乎无限，例如：插入视频等等；
    - 新增配置项`htmlDecode`，表示是否开启HTML标签识别和解析，但是为了安全性，默认不开启；
    - 新增识别和解析HTML标签的示例；
    
- 新增插入链接、锚点链接、预格式文本和代码块的弹出对话框层；
    - 弹出层改为使用固定定位；
    - 新增动态创建对话框的方法 `createDialog()`；
    - 新增静态属性`editormd.codeLanguages`，用于存放代码语言列表；

- 开始支持图片上传；
    - 新增添加图片（上传）弹出对话框层；
    - 支持基于iframe的跨域上传，并添加相应的示例（PHP版）；
    
- 开始支持自定义工具栏图标及操作处理；
    - 配置项`toolbarIcons`类型由数组更改为函数，返回一个图标按钮列表数组；
    - 新增配置项`toolbarHandlers` 和 `toolbarIconsTexts`，分别用于自定义按钮操作处理和按钮内容文本；
    - 新增方法`getToolbarHandles()`，用于可在外部使用默认的操作方法；
    - 新增成员属性`activeIcon`，可获取当前或上次点击的工具栏图标的jQuery实例对象；
    
- 新增表单取值、自定义工具栏、图片上传、多个Editor.md并存和动态加载Editor.md等多个示例；

- 新增插入锚点按钮和操作处理；

- 新增预览HTML内容窗口的关闭按钮，之前只能按ESC才能退出HTML全窗口预览；

- 新增多语言（l18n）及动态加载语言包支持；
    - 新增英语`en`和繁体中文`zh-tw`语言包模块；
    - 修改一些方法的内部实现以支持动态语言加载:
        - `toolbarHandler()`更为`setToolbarHandler()`；
        - `setToolbar()`方法包含`setToolbarHandler()`；
        - 新建`createInfoDialog()`方法；
	    - 修改`showInfoDialog()`和`hideInfoDialog()`方法的内部实现等；

- 修改多次Bug，并优化触摸事件，改进对iPad的支持；

- 工具栏新增清空按钮和清空方法`clear()`，解决工具栏文本会被选中出现蓝底的问题;

- 配置项`tocStartLevel`的默认值由2改为1，表示默认从H1开始生成ToC；

- 解决IE8下加载出错的问题；
    - 新增两个静态成员属性`isIE`和`isIE8`，用于判断IE8；
    - 由于IE8不支持FlowChart和SequenceDiagram，默认在IE8下不加载这两个组件，无论是否开启；

- 新增Zepto.js的支持；
	- 为了兼容Zepto，某些元素在操作处理上不再使用`outerWidth()`、`outerHeight()`、`hover()`、`is()`等方法；
	- 为了避免修改flowChart.js和sequence-diagram.js的源码，所以想支持flowChart或sequenceDiagram得加上这一句：`var jQuery = Zepto;`；

- 新增`editormd.$name`属性，修改`editormd.homePage`属性的新地址；

- `editormd.markdownToHTML()`新增方法返回一个jQuery实例对象；
    - 该实例对象定义了一个`getMarkdown()`方法，用于获取Markdown源代码；
    - 该实例对象定义了一个`tocContainer`成员属性，即ToC列表的父层的jQuery实例对象；

- 新增只读模式；
    - 新增配置项`readOnly`，默认值为`false`，即可编辑模式；
    - 其他相关改动；

- 新增方法`focus()`、`setCursor()`、`getCursor()`、`setSelection()`、`getSelection()`、`replaceSelection()`和`insertValue()`方法，并增加对应的示例；

- 新增配置项`saveHTMLToTextarea`，用于将解析后的HTML保存到Textarea，以供提交到后台程序；
    - `getHTML()`方法必须在`saveHTMLToTextarea == true`的情况下才能使用；
    - 新增`getHTML()`方法的别名`getTextareaSavedHTML()`方法；
    - 新增方法`getPreviewedHTML()`，用于获取预览窗口的HTML；

- 修复了一些大大小小的Bugs；

#####v1.1.1

- 接受一个pull请求，修复了`getHTML ()`和`getPreviewedHTML()`方法中的３处错误；

#####v1.1.2

- 修复Bug[＃10](https://github.com/pandao/editor.md/issues/10)；
- 修复Bug[＃12](https://github.com/pandao/editor.md/issues/12)；

#####v1.1.3

- 修复Bug[＃14](https://github.com/pandao/editor.md/issues/14)；
- 修复Bug[＃15](https://github.com/pandao/editor.md/issues/15)；

#####v1.1.4

- 修复Bug[＃17](https://github.com/pandao/editor.md/issues/17)；
    - 修改了`getToolbarHandles()`和`setToolbarHandler()`方法；
- 从`editormd.scss`中分离出`editormd.logo.scss`，并生成`editormd.logo.css`，以便单独使用；
    - 同时修改了`Gulpfile.js`的相应任务；
    
#####v1.1.5

- 修复Bug[＃18](https://github.com/pandao/editor.md/issues/18)；
    - 修改了`showInfoDialog()`和`createInfoDialog()`方法；
    - 新增`infoDialogPosition()`方法；
    
- 修复Bug[＃20](https://github.com/pandao/editor.md/issues/20)；
    - 修改了引用的处理函数；
    - 插入的headers的`#`号后面都加上了一个空格；

#####v1.1.6

修复多处Bug，具体如下：
    
- 修复Bug[#23](https://github.com/pandao/editor.md/issues/23)，即Headers的id属性的重复及中文问题；
    - 修改了`editormd.markedRenderer()`方法；

- 修复Bug[#24](https://github.com/pandao/editor.md/issues/24)；
    - 修改了`setMarkdown()`、`clear()`和`loadedDisplay()`方法的内部实现；
    - 新增了`katexRender()`、`flowChartAndSequenceDiagramRender()`、`previewCodeHighlight()`方法；
    
- 修复有些情况下无法保存Markdown源文档到textarea的问题；
    - 修改了`setCodeMirror()`、`recreateEditor()`等方法；

- 修改了以上Bug及部分相关示例文件；

#####v1.1.7

修复多处Bug，具体如下：

- 修复Bug[#25](https://github.com/pandao/editor.md/issues/25)；
    - 修改了`loadedDisplay()`方法，将`settings.onload`移动了`CodeMirror.on("change")`事件注册后再触发；

- 修复Bug[#26](https://github.com/pandao/editor.md/issues/26)；
    - 修改了`saveToTextareas()`方法；
    - 新增`state.loaded`和`state.watching`两个属性；

- 修改了以上Bug相关示例文件；

#####v1.1.8

改进功能，具体如下：

- 改进[#27](https://github.com/pandao/editor.md/issues/27)；
    - 新增配置项`matchWordHighlight`，可选值有：`true, false, "onselected"`，默认值为`true`，即开启自动匹配和标示相同单词；

- 改进[#28](https://github.com/pandao/editor.md/issues/28)；
    - 将`jquery.min.js`、`font-awesome.min.css`、`github-markdown.css`移除（这是一个疏忽，它们不是动态加载的依赖模块或者不需要的，避免不必要的硬盘空间占用）；

- 修改了所有相关的示例文件；
