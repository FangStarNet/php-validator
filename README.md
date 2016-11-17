# PHP-Validator (yunhack/php-validator)


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


**Note:** ```PHP``` ```parameter check``` ```file check``` ```validation```




# summary
　　这是一个基于PHP 5.6以上版本的参数校验组件，组件v1.0.0版本作者 Qvil_Young 在使用Laravel的Validation组件后，发现其有BUG或用法有歧义，文档不正确等带来诸多不便之处...
因此决定自己开发一个好用的组件

　　该组件独立于项目中，不依赖任何其他组件和PHP特殊少见的扩展。

　　该组件针对HTTP(s)协议下Web请求和响应的场景，提供了N多种对变量类型和常见字符串类型进行严格校验的规则，同时提供了变量类型的转换和别名命名！

　　该组件暂不打算耦合ORM去校验字段的唯一性，作者觉得那样做是多余的！

###### 以下是php-validator组件的特性：

  * 支持对上传的文件进行校验(比如文件类型，文件大小区间)
  * 准确校验PHP的各种数据类型、以及常见字符串的数据类型(比如字母、数字、邮箱、中国地区的手机号、IP地址和URL等)
  * 支持多个参数之间的关联性校验(比如当参数a传入时，参数b也必须传入)
  * 准确判断数值的大小和长度，以及它们的区间(比如，校验年龄在18岁到59岁之间，字符串长度在12到20个之间)
  * 支持浮点类型的准确校验，并且支持精度转换(比如将money字段转成2为小数，同时会校验原参数是否为字符串)
  * 高级用法：支持字符串形式的一维数组校验，比如字符串"[1,2,3,4]"的校验，同时支持将其转成PHP数组，数组中的元素也可以指定数据类型
  * 支持多国语言，语言文件默认路径，使用Laravel5.2的项目结构，放于 resources/lang/下。如有特殊需求也可以配置

###### 以下是QP v1.0.0 版本功能上不足之处：

  * QP文档正在编写中...
  * 可能存在BUG，但是参数校验规则已经稳定，不会改变。如发现BUG，可以共同维护

# Install

使用Composer工具安装项目(如果还有安装Composer，请自行搜索如何安装)

Via Composer

``` bash
$ composer require yunhack/q-phalcon
```

# Document

Sorry, 文档正在更新中...敬请期待


# Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


# Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


# Security

If you discover any security related issues, please email qvil_yong@163.com instead of using the issue tracker.


# Credits

- [Qvil Young][link-author]
- [All Contributors][link-contributors]


# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-version]: https://img.shields.io/packagist/v/yunhack/php-validator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/yunhack/php-validator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/yunhack/php-validator
[link-downloads]: https://packagist.org/packages/yunhack/php-validator
[link-author]: https://github.com/Qvil-Young
[link-contributors]: ../../contributors
[link-Download_Phalcon]: https://phalconphp.com/en/download
[link-Download_Redis]: http://redis.io/download
