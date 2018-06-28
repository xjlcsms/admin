<?php

namespace Ku\Thumb\Gif;

class Mark {

    /**
     * 数据流结束标识 固定值为 0x3B
     */
    const TRAILER  = 0x3B;

    /**
     * 图像域的结束, 固定为0x00
     */
    const TERMINATOR = 0x00;

    /**
     * 图像标识符开始，固定值为 0x2C
     */
    const SEPARTOR = 0x2C;

    /**
     * 扩展块，固定值0x21
     */
    const EXTENSION = 0x21;

    /**
     * 图形控制扩展块，固定值0xF9
     */
    const EXTENSION_GRAPHIC = 0xF9;

    /**
     * 注释块，固定值 0xFE
     */
    const EXTENSION_COMMENT = 0xFE;

    /**
     * 应用程序扩展块，固定值 0xFF
     */
    const EXTENSION_APPLICATION = 0xFF;

    /**
     * 图形文本扩展块, 固定值 0x01
     */
    const EXTENSION_PLAINTEXT = 0x01;

}
