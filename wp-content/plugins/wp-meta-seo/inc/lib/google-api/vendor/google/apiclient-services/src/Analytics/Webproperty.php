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

class Webproperty extends \WPMSGoogle\Model
{
  public $accountId;
  protected $childLinkType = WebpropertyChildLink::class;
  protected $childLinkDataType = '';
  public $created;
  public $dataRetentionResetOnNewActivity;
  public $dataRetentionTtl;
  public $defaultProfileId;
  public $id;
  public $industryVertical;
  public $internalWebPropertyId;
  public $kind;
  public $level;
  public $name;
  protected $parentLinkType = WebpropertyParentLink::class;
  protected $parentLinkDataType = '';
  protected $permissionsType = WebpropertyPermissions::class;
  protected $permissionsDataType = '';
  public $profileCount;
  public $selfLink;
  public $starred;
  public $updated;
  public $websiteUrl;

  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
  }
  /**
   * @param WebpropertyChildLink
   */
  public function setChildLink(WebpropertyChildLink $childLink)
  {
    $this->childLink = $childLink;
  }
  /**
   * @return WebpropertyChildLink
   */
  public function getChildLink()
  {
    return $this->childLink;
  }
  public function setCreated($created)
  {
    $this->created = $created;
  }
  public function getCreated()
  {
    return $this->created;
  }
  public function setDataRetentionResetOnNewActivity($dataRetentionResetOnNewActivity)
  {
    $this->dataRetentionResetOnNewActivity = $dataRetentionResetOnNewActivity;
  }
  public function getDataRetentionResetOnNewActivity()
  {
    return $this->dataRetentionResetOnNewActivity;
  }
  public function setDataRetentionTtl($dataRetentionTtl)
  {
    $this->dataRetentionTtl = $dataRetentionTtl;
  }
  public function getDataRetentionTtl()
  {
    return $this->dataRetentionTtl;
  }
  public function setDefaultProfileId($defaultProfileId)
  {
    $this->defaultProfileId = $defaultProfileId;
  }
  public function getDefaultProfileId()
  {
    return $this->defaultProfileId;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setIndustryVertical($industryVertical)
  {
    $this->industryVertical = $industryVertical;
  }
  public function getIndustryVertical()
  {
    return $this->industryVertical;
  }
  public function setInternalWebPropertyId($internalWebPropertyId)
  {
    $this->internalWebPropertyId = $internalWebPropertyId;
  }
  public function getInternalWebPropertyId()
  {
    return $this->internalWebPropertyId;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setLevel($level)
  {
    $this->level = $level;
  }
  public function getLevel()
  {
    return $this->level;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param WebpropertyParentLink
   */
  public function setParentLink(WebpropertyParentLink $parentLink)
  {
    $this->parentLink = $parentLink;
  }
  /**
   * @return WebpropertyParentLink
   */
  public function getParentLink()
  {
    return $this->parentLink;
  }
  /**
   * @param WebpropertyPermissions
   */
  public function setPermissions(WebpropertyPermissions $permissions)
  {
    $this->permissions = $permissions;
  }
  /**
   * @return WebpropertyPermissions
   */
  public function getPermissions()
  {
    return $this->permissions;
  }
  public function setProfileCount($profileCount)
  {
    $this->profileCount = $profileCount;
  }
  public function getProfileCount()
  {
    return $this->profileCount;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  public function setStarred($starred)
  {
    $this->starred = $starred;
  }
  public function getStarred()
  {
    return $this->starred;
  }
  public function setUpdated($updated)
  {
    $this->updated = $updated;
  }
  public function getUpdated()
  {
    return $this->updated;
  }
  public function setWebsiteUrl($websiteUrl)
  {
    $this->websiteUrl = $websiteUrl;
  }
  public function getWebsiteUrl()
  {
    return $this->websiteUrl;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Webproperty::class, 'WPMSGoogle_Service_Analytics_Webproperty');
