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

class QuotaStatus extends \WPMSGoogle\Model
{
  public $consumed;
  public $remaining;

  public function setConsumed($consumed)
  {
    $this->consumed = $consumed;
  }
  public function getConsumed()
  {
    return $this->consumed;
  }
  public function setRemaining($remaining)
  {
    $this->remaining = $remaining;
  }
  public function getRemaining()
  {
    return $this->remaining;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QuotaStatus::class, 'WPMSGoogle_Service_AnalyticsData_QuotaStatus');
