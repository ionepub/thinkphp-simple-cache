<?php

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
    function simpleCache($cacheName = '', $refresh = false, $key = '', $data = array(), $tableName = '', $condition = array(), $field = '*', $order = ''){
        if(!$cacheName){
            return false;
        }
        // option: md5加密缓存文件名
        $cacheName = md5($cacheName);
        
        $cacheData = F($cacheName);
        if(!$cacheData || $refresh == true){
            // 不存在缓存或需要刷新
            $cacheData = array();
            // $tableName优先
            if($tableName != ""){
                if($order){
                    $sourceData = M($tableName)->where($condition)->field($field)->order($order)->select();
                }else{
                    $sourceData = M($tableName)->where($condition)->field($field)->select();
                }
            }else{
                $sourceData = $data;
            }
            // 如果$key为空或数据数组是一维数组，直接缓存
            if(!$key || count($sourceData) == count($sourceData, 1)){
                F($cacheName, $sourceData);
                return $sourceData;
            }elseif($key && count($sourceData) != count($sourceData, 1)){
                // 如果$key不为空 且数据数组是二维数组
                foreach ($sourceData as $k => $item) {
                    if(isset($item[$key])){
                        $cacheData[$item[$key]] = $item;
                    }else{
                        $cacheData[$k] = $item;
                    }
                }
                F($cacheName, $cacheData);
            }
        }
        return $cacheData;
    }