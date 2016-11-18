# PHP-Validator (fangstar/php-validator)


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


**Note:** ```PHP``` ```parameter check``` ```file check``` ```validation```




# summary
　　这是一个基于PHP5.6以上版本的PHP参数校验组件，其v1.0.0版本的作者Qvil_Young，使用过Laravel5.2的Validation组件后，发现其存在校验BUG、用法歧义之处，并且文档不正确等带来诸多问题...
因此才决定自己开发一个好用的组件，独立于任何框架，和Laravel分离开来，功能简单强大！

　　该组件不依赖任何其他组件，以及PHP特殊少见的扩展。

　　该组件针对HTTP(S)协议下Web请求和响应的场景，提供了N多种对变量类型和常见字符串类型进行严格校验的规则，同时提供了变量类型的转换和别名命名！

　　该组件暂不打算耦合ORM去校验字段的唯一性，作者觉得那样做是多余的！

###### 以下是php-validator组件的特性：

  * 支持对上传的文件进行校验(比如文件类型，文件大小区间)
  * 准确校验PHP的各种数据类型、以及常见字符串的数据类型(比如字母、数字、邮箱、中国地区的手机号、IP地址和URL等)
  * 支持多个参数之间的关联性校验(比如当参数a传入时，参数b也必须传入)
  * 准确判断数值的大小和长度，以及它们的区间(比如，校验年龄在18岁到59岁之间，字符串长度在12到20个之间)
  * 支持浮点类型的准确校验，并且支持精度转换(比如将money字段转成2为小数，同时会校验原参数是否是浮点数)
  * 高级用法：支持字符串形式的一维数组校验，比如字符串"[1,2,3,4]"的校验，同时支持将其转成PHP数组，数组中的元素也可以指定数据类型
  * 支持自定义提示文案，语言文件默认路径使用Laravel5.2的项目结构，放于 resources/lang/下。如有特殊需求也可以配置

###### 以下是QP v1.0.0 版本功能可能的不足之处：

  * 组件对外提供的方法和校验规则已经稳定，不会改变。如果你发现BUG，请发邮件给作者！[Qvil Young][link-author]





# Install

使用Composer工具安装项目(使用方法，自行学习)

Via Composer

``` bash
$ composer require fangstar/php-validator
```

将lang文件夹下面的语言包文件，拷贝到和verdor目录同级下的 resources/lang/ 文件夹下。

或者根据自身需求，修改config.php文件的lang配置项

基础用法

``` php
<?php
use FangStarNet\PHPValidator\Validator;

$data = $_GET;
Validator::make($data, [
    "id" => "present|alpha_num|length:32", // 校验id字段必传，且由数字字母组成，长度为32
]);
if (Validator::has_fails()) {
    echo Validator::error_msg(); // 校验不通过，打印提示信息(默认使用语言包中的文案)
    exit;
} else {
    echo "参数校验已经通过";
}

```





# Document

[组件详细使用文档，传送门在此！](Document.md)






# Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.





# Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.






# Security

If you discover any security related issues, please email yangqingwu@fangstar.net instead of using the issue tracker.






# Credits

- [Qvil Young][link-author]
- [All Contributors][link-contributors]







# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.








[ico-version]: https://img.shields.io/packagist/v/fangstar/php-validator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/fangstar/php-validator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/fangstar/php-validator
[link-downloads]: https://packagist.org/packages/fangstar/php-validator
[link-author]: https://github.com/Qvil-Young
[link-contributors]: ../../contributors
