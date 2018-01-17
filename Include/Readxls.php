<?php

namespace BigData;

use PHPExcel_IOFactory;
use PHPExcel_Settings;

trait Readxls {
    protected $objPHPExcelReader;

    function getData($file, $beginRow = 2) {
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        $this->objPHPExcelReader = PHPExcel_IOFactory::load($file);
        foreach($this->objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            $i = 0;
            foreach($sheet->getRowIterator() as $row)  //逐行处理
            {
                if($row->getRowIndex() < $beginRow)  //确定从哪一行开始读取
                {
                    continue;
                }
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    $data[$i][] = $cell->getCalculatedValue(); //获取cell中数据
                }
                $i++;
            }
        }
        return $data ?: [];
    }

    function getSurfaceData($file, $beginRow = 2) {
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        $this->objPHPExcelReader = PHPExcel_IOFactory::load($file);
        foreach($this->objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            $i = 0;
            foreach($sheet->getRowIterator() as $row)  //逐行处理
            {
                if($row->getRowIndex() < $beginRow)  //确定从哪一行开始读取
                {
                    continue;
                }
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    $data[$i][] = $cell->getValue(); //获取cell中数据
                }
                $i++;
            }
        }
        return $data ?: [];
    }
}