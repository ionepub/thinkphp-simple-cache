# ThinkPHP 简单缓存函数

> ThinkPHP版本：3.2.3，其他版本未测试

## 功能

对指定的数据数组或数据表数据进行缓存（文件缓存），减少数据库请求


## 参数

```php
/**
 * 更新/获取表数据缓存
 * 缓存不存在或需要刷新时，更新缓存，并返回新数据，否则返回缓存数据
 * @param string $cacheName 缓存名称
 * @param bool $refresh 是否刷新缓存，默认false，当为true时，将重新构建缓存
 * @param string $key 数组键名，可以是$data二维数组中的某个键名，或数据表某个字段名，默认为''，缓存数组键名为数字
 * @param array $data 待缓存的数据数组
 * @param string tableName 数据表名，优先于$data参数，不为空时，将查询表数据并缓存
 * @param array $condition 查询数据表的条件，$tableName不为空时可用
 * @param string $field 要取出的字段列表，$tableName不为空时可用
 * @param string $order 查询数据表的排序，$tableName不为空时可用
 */
function simpleCache($cacheName = '', $refresh = false, $key = '', $data = array(), $tableName = '', $condition = array(), $field = '*', $order = ''){}
```


## 使用

将函数体复制到`Common/Common/function.php`中即可。

## 示例

### 1. 数据数组数据保存

```php
$data = array(
	array(
		'id'=>10,
		'name'=>'test',
	),
	array(
		'id'=>11,
		'name'=>'test2',
	),
);

# 直接保存原数据，读取时跟原数组一致
simpleCache('test', true, '', $data);

# 以数组中某个键值做为键名保存
simpleCache('test', true, 'id', $data);

/* > result：
Array
(
    [10] => Array
        (
            [id] => 10
            [name] => test
        )
    [11] => Array
        (
            [id] => 11
            [name] => test2
        )
)
*/
```

### 2. 表数据保存

```php
$where = array(
    'id'    =>  array('gt', 10)
);
$cacheName = 'test';
$isRefresh = true;
$key = '';
$key2 = 'id';
$data = array();
$tableName = 'test';
$field = '*';
$order = 'id desc';

# 直接保存数据表数据，读取时跟原数组一致
$cache = simpleCache($cacheName, $isRefresh, $key, $data, $tableName, $field, $order);

# 以数据数组中某个键值做为键名保存
$cache = simpleCache($cacheName, $isRefresh, $key2, $data, $tableName, $field, $order);

```

### 3. 数据读取

```php
$cache = simpleCache('test');
```

## 说明

- 函数借助ThinkPHP自带的F方法实现快速缓存，缓存的文件地址是默认的`Runtime/Data/md5(cacheName).php`
- 函数对文件名作了md5加密处理，不需要的可以注释掉

```
// option: md5加密缓存文件名
$cacheName = md5($cacheName);
```






