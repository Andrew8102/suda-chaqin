# 每日查寝

## 目的

每天班主任都让我统计班级同学归寝情况，之前我每天都得群里问，然后复制到腾讯文档里面，最后截图给老师，好不麻烦！

现在我直接用小程序帮我半自动解决了以下问题：多端数据同步，免于每天十多次的复制，免于每次都要数清楚哪个宿舍没打卡的窘境，解放发展了生产力，让我能在考研期间每天11点半在群里报告完毕后上床睡觉。

## 功能

- 全功能表单收集每日归寝打卡信息
- html2canvas截图发送免于微信发送图片的时候需要剪裁
- 查看昨天的数据
- 云端SQL数据库数据每日更新同步
- EcmaScript特性取差集，直接生成当日未打卡情况语句
- 半自动化节约了大量精力和时间，让没什么意义的事情尽量减少影响，可以早点睡觉

## 技术栈

1. PHP7

2. Vue.js和jquery

3. MySQL

4. Bootstrap3

## 使用说明

1. 首先用sql安装
2. 其次修改config.php 中的数据库信息
3. 根据数据事例导入自己数据
4. 正常使用即可

## 使用截图

![WX20201114-100555@2x](https://tva1.sinaimg.cn/large/0081Kckwgy1gkoh7y9xc5j312d0u045l.jpg)

![WX20201114-100732@2x](https://tva1.sinaimg.cn/large/0081Kckwgy1gkoh8cyf7tj30sq0rgn0p.jpg)

## 感谢名单

感谢Evan Lou，感谢Bootstrap，感谢世界上最好的PHP,感谢魔改的html2canvas让截图不那么模糊

## License

MIT License

## last update

2020-11-14
