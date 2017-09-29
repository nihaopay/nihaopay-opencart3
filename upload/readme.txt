# nihaopay-opencart3
opencart 3.0.2.0
 如有其他问题请问liang.qin@aurfy.com 获取帮助
 
 安装说明：
(一)
1.直接上传nihaopay.payment.ocmod.zip；
2.在系统管理后台(Extensions->Installer->Upload)
3.在系统管理后台(Extensions->Extensions->Choose the extension type(Payments))
添加 Payment Method
依次填写Token,Transaction Server,Geo Zone,Total,Order Status,Status,Sort Order
Token              —- NihaoPay系统生成交易Token
Transaction Server —- Live(生产环境),Test(测试环境)
Geo Zone	       —- 默认 All zones
Total		       —- The checkout total the order must reach before this payment method becomes active(可不填，默认为空)
Order Status       —- 订单支付完成后显示的订单状态
Status             —- 是否启用
Sort Order         —- 可不填，默认为空
 
(二)
1.解压nihaopay.payment.ocmod.zip
2.解压后upload文件夹下的两个文件FTP方式上传到网站根目录(注:选择所有不替换上传)



注意：
1.检查后台邮件服务是否配置(在发送邮件时会出错或无限等待中)
 
2.请勿随意修改程序，将可能出现掉单情况
 
3.接口安装后，如额外添加其它组件或者模块，请重新检测交易数据是否正常

4.更改系统默认币种后，请刷新汇率

说明
================
NihaoPay插件，支持:Credit Card payment,UnionPay Online payment,AliPay online payment,WechatPay online payment



备注
==================

任何问题请联系  liang.qin@aurfy.com 
