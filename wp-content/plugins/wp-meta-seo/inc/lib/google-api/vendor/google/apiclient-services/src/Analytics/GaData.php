<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace WPMSGoogle\Service\Analytics;

class GaData extends \WPMSGoogle\Collection
{
  protected $collection_key = 'rows';
  protected $columnHeadersType = GaDataColumnHeaders::class;
  protected $columnHeadersDataType = 'array';
  public $containsSampledData;
  public $dataLastRefreshed;
  protected $dataTableType = GaDataDataTable::class;
  protected $dataTableDataType = '';
  public $id;
  public $itemsPerPage;
  public $kind;
  public $nextLink;
  public $previousLink;
  protected $profileInfoType = GaDataProfileInfo::class;
  protected $profileInfoDataType = '';
  protected $queryType = GaDataQuery::class;
  protected $queryDataType = '';
  public $rows;
  public $sampleSize;
  public $sampleSpace;
  public $selfLink;
  public $totalResults;
  public $totalsForAllResults;

  /**
   * @param GaDataColumnHeaders[]
   */
  public function setColumnHeaders($columnHeaders)
  {
    $this->columnHeaders = $columnHeaders;
  }
  /**
   * @return GaDataColumnHeaders[]
   */
  public function getColumnHeaders()
  {
    return $this->columnHeaders;
  }
  public function setContainsSampledData($containsSampledData)
  {
    $this->containsSampledData = $containsSampledData;
  }
  public function getContainsSampledData()
  {
    return $this->containsSampledData;
  }
  public function setDataLastRefreshed($dataLastRefreshed)
  {
    $this->dataLastRefreshed = $dataLastRefreshed;
  }
  public function getDataLastRefreshed()
  {
    return $this->dataLastRefreshed;
  }
  /**
   * @param GaDataDataTable
   */
  public function setDataTable(GaDataDataTable $dataTable)
  {
    $this->dataTable = $dataTable;
  }
  /**
   * @return GaDataDataTable
   */
  public function getDataTable()
  {
    return $this->dataTable;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setItemsPerPage($itemsPerPage)
  {
    $this->itemsPerPage = $itemsPerPage;
  }
  public function getItemsPerPage()
  {
    return $this->itemsPerPage;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setNextLink($nextLink)
  {
    $this->nextLink = $nextLink;
  }
  public function getNextLink()
  {
    return $this->nextLink;
  }
  public function setPreviousLink($previousLink)
  {
    $this->previousLink = $previousLink;
  }
  public function getPreviousLink()
  {
    return $this->previousLink;
  }
  /**
   * @param GaDataProfileInfo
   */
  public function setProfileInfo(GaDataProfileInfo $profileInfo)
  {
    $this->profileInfo = $profileInfo;
  }
  /**
   * @return GaDataProfileInfo
   */
  public function getProfileInfo()
  {
    return $this->profileInfo;
  }
  /**
   * @param GaDataQuery
   */
  public function setQuery(GaDataQuery $query)
  {
    $this->query = $query;
  }
  /**
   * @return GaDataQuery
   */
  public function getQuery()
  {
    return $this->query;
  }
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  public function getRows()
  {
    return $this->rows;
  }
  public function setSampleSize($sampleSize)
  {
    $this->sampleSize = $sampleSize;
  }
  public function getSampleSize()
  {
    return $this->sampleSize;
  }
  public function setSampleSpace($sampleSpace)
  {
    $this->sampleSpace = $sampleSpace;
  }
  public function getSampleSpace()
  {
    return $this->sampleSpace;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  public function setTotalResults($totalResults)
  {
    $this->totalResults = $totalResults;
  }
  public function getTotalResults()
  {
    return $this->totalResults;
  }
  public function setTotalsForAllResults($totalsForAllResults)
  {
    $this->totalsForAllResults = $totalsForAllResults;
  }
  public function getTotalsForAllResults()
  {
    return $this->totalsForAllResults;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GaData::class, 'WPMSGoogle_Service_Analytics_GaData');
