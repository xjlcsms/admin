<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel\Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */


namespace PHPExcel;

/**
 * PHPExcel\Writer_Excel2007_WriterPart
 *
 * @category   PHPExcel
 * @package    PHPExcel\Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
abstract class Writer_Excel2007_WriterPart
{
    /**
     * Parent IWriter object
     *
     * @var PHPExcel\Writer_IWriter
     */
    private $_parentWriter;

    /**
     * Set parent IWriter object
     *
     * @param PHPExcel\Writer_IWriter    $pWriter
     * @throws PHPExcel\Writer_Exception
     */
    public function setParentWriter(Writer_IWriter $pWriter = null) {
        $this->_parentWriter = $pWriter;
    }

    /**
     * Get parent IWriter object
     *
     * @return PHPExcel\Writer_IWriter
     * @throws PHPExcel\Writer_Exception
     */
    public function getParentWriter() {
        if (!is_null($this->_parentWriter)) {
            return $this->_parentWriter;
        } else {
            throw new Writer_Exception("No parent PHPExcel\Writer_IWriter assigned.");
        }
    }

    /**
     * Set parent IWriter object
     *
     * @param PHPExcel\Writer_IWriter    $pWriter
     * @throws PHPExcel\Writer_Exception
     */
    public function __construct(Writer_IWriter $pWriter = null) {
        if (!is_null($pWriter)) {
            $this->_parentWriter = $pWriter;
        }
    }

}
