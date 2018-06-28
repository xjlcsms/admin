#!/bin/bash

#########################
# 复制 conf 目录下所有 *\.ini\.sample 为 *\.ini 文件
# 免去第一次 clone 项目多次修改文件
# author chenjiayao
#########################
rename  's/.ini.sample/.ini/' *.ini.sample

for i in `ls *.ini`;
    do
        cp $i ${i}.sample
    done

# 执行之后文件没用，删除
# rm -f rename.sh