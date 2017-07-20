#!/usr/bin/env bash

root_dir=$(cd "$(dirname "$0")"; cd ..; pwd)

chown -R nobody.nobody $0
chmod -R +x $root_dir/bin/*

# 需要创建的目录
mk_dirs=("public/Runtime")

# 创建目录并设定权限
for dir in ${mk_dirs[@]}; do
    dir=$root_dir/$dir
    if [ ! -d $dir ]; then
        mkdir -p $dir
        echo "Created Directory: $dir"
    fi
    chown -R nobody.nobody $dir
    chmod -R 755 $dir
done

rm_dirs=("public/Runtime/Data" "public/Runtime/Cache" "public/Runtime/Temp")
# 删除缓存目录
for dir in ${rm_dirs[@]}; do
    dir=$root_dir/$dir
    if [ -d $dir ]; then
        rm -R $dir
        echo "Delete Directory: $dir"
    fi
done

# 删除缓存配置
filename=$root_dir"/public/Runtime/common~runtime.php"
if [ -f $filename ]; then
    rm $filename
    echo "remove $filename"
fi