# PHP-Validator (fangstar/php-validator) 参考文档


## 组件重要功能预览

  * 支持对上传的文件进行校验(比如文件类型，文件大小区间)
  * 准确校验PHP的各种数据类型、以及常见字符串的数据类型(比如字母、数字、邮箱、中国地区的手机号、IP地址和URL等)
  * 支持多个参数之间的关联性校验(比如当参数a传入时，参数b也必须传入)
  * 准确判断数值的大小和长度，以及它们的区间(比如，校验年龄在18岁到59岁之间，字符串长度在12到20个之间)
  * 支持浮点类型的准确校验，并且支持精度转换(比如将money字段转成2为小数，同时会校验原参数是否是浮点数)
  * 高级用法：支持字符串形式的一维数组校验，比如字符串"[1,2,3,4]"的校验，同时支持将其转成PHP数组，数组中的元素也可以指定数据类型
  * 支持自定义提示文案，语言文件默认路径使用Laravel5.2的项目结构，放于 resources/lang/下。如有特殊需求也可以配置





---
## 基础用法(示例演示)

``` php
use FangStarNet\PHPValidator\Validator;

$data = $_GET;

Validator::make($data, [
    "id" => "present|alpha_num|length:32",
],[
    "id.length" => "id不合法",
]);
if (Validator::has_fails()) {
    echo Validator::error_msg();
} else {
    echo "参数校验已经通过";
}
```

代码解析：

\#1 引用组件 FangStarNet\PHPValidator\Validator

\#3 请求参数(数组格式)

\#5 make()方法：制定校验规则，并且开始处理

 * make方法传入3个参数，第一个参数是被校验的数组
 * 第二个参数是校验规则
     * 具体的校验规则，该文档后续会详细展示
 * 第三个参数是个性化文案
     * id.length表示针对字段id的length规则，如果校验不通过，文案采用用户制定的"id不合法"

\#10 has_fails()方法：判断参数校验是否存在错误(即参数校验是否未通过)，true表示未通过，false表示通过

\#11 error_msg()方法：显示当然规则的提示文档(仅在校验未通过时使用)





---
## 校验优先级

#### 第一优先级校验关键字(按类型分组)：

    1.文件属性校验：
    
    file_exists、file_type_in、file_max、file_min、file_size_between
    
    2.必传字段校验：
    
    present
    
    3.字段关联性校验：
    
    required_with、required_with_all、required_without、required_without_all、same、different
    
#### 字段的第二优先级校验关键字(按类型分组)：

    1.常用字符组合校验：
    
    alpha、num、alpha_num、alpha_dish、var、ip、url、email、mobile、json
    
    2.时间格式校验：
    
    timestamp、date_format
    
    3.正则表达式
    
    regex
    
    4.变量类型校验
    
    string、boolean、integer、float、array、object、object_of
    
    5.字符串形式的变量类型校验
    
    integer_str、float_str、numeric_str、array_str
    
    6.大小和长度范围校验：
    
    max、length_max、min、length_min、length、between、length_between、in、not_in
    
    7.非空校验
    
    filled
    
    8.唯一校验
    
    distinct
    
    9.特殊关键字：类型转换、别名
    
    to_type、alias
    
关于优先级和校验流程的说明：

　　优先级总的一个原则：第二优先级只针对字段校验，校验第二优先级，必须是基于字段存在的条件下成立的

　　举个简单的例子："age" => "min:18"

　　校验age字段的值最小为18，前提是age字段存在的情况下才去校验的，如果不存在，则跳过校验


文件校验的特殊性：

　　因为文件属性校验是基于文件存在的条件下成立的，因此，文件校验隐含一个present规则

　　针对文件属性校验，没有第二校验规则一说

　　在HTTP请求中，上传的文件参数放于form-data中，类型为file；可以用post-man工具进行测试


字段关联性：

　　组件提供字段之间关联性校验，举个简单的例子："a" => "same:b"

　　参数a和b的值必须一样，如果参数未传入，其值为null




---
## 校验规则详解

学到这里，你已经明白了组件是怎么去校验参数的，并且学会了怎么去写基本的校验规则！

那么，这一章将总结组件提供的所有校验规则和它们的意义：

#### present
　　未传入/空字符串/空数组：表示不存在，其他情况都认为参数存在

#### alpha
　　所有字符都是字母 a-z和A-Z

#### num
　　所有字符都是数字 0-9

#### alpha_num
　　所有字符都是数字或数字 0-9 a-z A-Z

#### alpha_dish
　　字符串由字母、数字、下划线和短横线组成 0-9 a-z A-Z _ -

#### var
　　字符串必须是有效的变量名，即字母或下划线开头，并且整个字符串由字符数字和下划线组成

#### ip
　　参数必须是有效的IP地址

#### url
　　参数必须是是有效的URL

#### email
　　参数必须是有效的邮箱地址

#### mobile
　　参数必须是有效的电话号码，中国地区(2016年)：1开头，第2为是3-8的数字，后面是9位数字

#### json
　　参数必须是有效的JSON字符串

#### timestamp
　　参数必须是有效的时间戳

#### date_format
　　参数必须满足指定的日期格式，比如："dt" => "date_format:Y-m-d H:i:s"

#### regex
　　参数必须满足指定的正则表达式，比如："p" => "regex:Y-m-d H:i:/^abc/"

#### string
　　参数必须是字符串类型

#### boolean
　　参数必须是布尔类型

#### integer
　　参数必须是整数(不区分精度)类型

#### float
　　参数必须是浮点数(不区分精度)类型

#### array
　　参数必须是数组类型

#### object
　　参数必须是对象类型

#### object_of
　　参数必须是指定的对象类型，比如："obj" => "object_of:\App\Log"

#### integer_str
　　参数必须是整数形式的字符串

#### float_str
　　参数必须是小数形式的字符串

#### numeric_str
　　参数必须是数字形式的字符串

#### array_str
　　参数必须是字符串形式的字符串，比如 "[1,2,3]" 这样的字符串

#### max
　　参数的值，最大为指定的值，比如："p" => "max:18"

#### length_max
　　参数的长度，最大为指定的值(一个汉字算一个)，比如："p" => "length_max:12" // 表示最长为12个字符

#### min
　　参数的值，最小为指定的值，比如："p" => "max:0"

#### length_min
　　参数的长度，最大为指定的值(一个汉字算一个)，比如："p" => "length_min:6" // 表示最短为6个字符

#### length
　　参数的长度，必须是指定的长度(一个汉字算一个)，比如："id" => "length:32" // 表示id字段必须是32个字符

#### between
　　参数的值，必须在指定的区间(区间从小到大)，比如："age" => "between:18,59"

#### length_between
　　参数的长度，必须在指定的区间(区间从小到大)，比如："pwd" => "length_between:6,16"

#### in
　　参数的值，必须在指定的数据中，比如："status" => "in:1,3,4"

#### not_in
　　参数的值，必须不在指定的数据中，比如："status" => "not_in:2,5"

#### filled
　　参数的值不能为空，内部使用empty函数校验，请参考PHP官方文档的empty函数

#### distinct
　　参数的值必须是数组，并且数组中不存在重复的值

#### different
　　针对参数的值，必须和指定参数的值不一样，比如："p" => "different:a" // 参数p和a的值必须不一样

#### same
　　针对参数的值，必须和指定参数的值一样，比如："p" => "same:a" // 参数p和a的值必须一样

#### required_with
　　当指定参数中，只要存在一个时，该参数也必须存在，比如："a" => "required_with:b" // 当参数b存在时，参数a也必须存在

#### required_with_all
　　当指定的所有参数都存在时，该参数也必须存在，比如："a" => "required_with_all:b,c" // 当参数b和c都存在时，参数a也必须存在

#### required_without
　　当指定参数中，只要不存在一个时，该参数必须存在，比如："a" => "required_without:b" // 当参数b不存在时，参数a必须存在

#### required_without_all
　　当指定所有参数都不存在时，该参数必须存在，比如："a" => "required_without_all:b,c" // 当参数b和c都不存在时，参数a必须存在

#### file_exists
　　校验指定的文件是否存在，比如："f" => "file_exists"

#### file_type_in
　　校验指定的文件类型，比如："f" => "file_type_in:png" // 文件格式不区分大小写

#### file_max
　　校验指定的文件大小，不能大于x(MB)，比如："f" => "file_max:0.5" // 最大不能超过0.5MB

#### file_min
　　校验指定的文件大小，不能小于x(MB)，比如："f" => "file_min:0.1" // 最小不能超过0.1MB

#### file_size_between
　　校验指定的文件大小，必须在指定的区间范围，单位(MB)，比如："f" => "file_size_between:0.1,0.5"




---
# 进阶用法

　　以下用法已经超出了单纯的参数校验范畴，但它们和参数校验存在着一定的关系！那就是变量类型转换和变量别名

##### 变量类型的转换

　　1.将数据insert到mysql时，常常需要插入整数或浮点数，如果数据库字段类型为int，但是插入的时候使用"123"这样的字符串的话，抱歉PDO异常！

　　2.一个严格的程序员往往需要明确变量类型，只有明确了变量类型，才能写得一手好代码

　　基于以上或更多的场景，往往我们希望在参数校验完毕后，转换变量类型，得到我们想要的数组。那么 to_type 就由此而来！

　　Validator::data() 方法返回处理后的参数数组！
　　
　　
``` php
Validator::make($data, [
    "money" => "float_str|to_type:scale:2",
]);
if (Validator::has_fails()) {
    echo Validator::error_msg();
} else {
    var_dump(Validator::data());
}
```

首先校验money字段是否为小数形式，校验通过后，var_dump()打印出来的money字段类型为float，并且四舍五入，保留两位小数

to_type支持的转换类型有：

    string、boolean、integer、float、array、object、str_array、scale

其中，string、boolean、integer、float的使用xxxval方法转换，比如：intval($v);

array和object使用强制转换，比如：(array) $v;

比较特殊的是 str_array、scale 的转换

##### str_array (目前只支持字符串格式的一维数组，比如"[1,2,3]")

　　首先校验是否为array_str形式的字符串

　　是的话，将其解开，转成 [0 => 1, 1 => 2, 2 => 3]的array类型

#### scale

　　首先校验参数是否为小数形式

　　是的话，将其用四舍五入的方法，保留指定的有效小数，最终是float类型





---
# Security

　　If you discover any security related issues, please email yangqingwu@fangstar.net instead of using the issue tracker.
