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

namespace WPMSGoogle\Service\AnalyticsData;

class Metadata extends \WPMSGoogle\Collection
{
  protected $collection_key = 'metrics';
  protected $dimensionsType = DimensionMetadata::class;
  protected $dimensionsDataType = 'array';
  protected $metricsType = MetricMetadata::class;
  protected $metricsDataType = 'array';
  public $name;

  /**
   * @param DimensionMetadata[]
   */
  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  /**
   * @return DimensionMetadata[]
   */
  public function getDimensions()
  {
    return $this->dimensions;
  }
  /**
   * @param MetricMetadata[]
   */
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  /**
   * @return MetricMetadata[]
   */
  public function getMetrics()
  {
    return $this->metrics;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Metadata::class, 'WPMSGoogle_Service_AnalyticsData_Metadata');
