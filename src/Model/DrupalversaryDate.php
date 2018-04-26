<?php

namespace Drupal\drupalversary\Model;

use Drupal\Core\Datetime\DrupalDateTime;

class DrupalversaryDate {

  /**
   * @var string
   */
  private $day;

  /**
   * @var string
   */
  private $month;

  public function __construct(int $created_date) {
    $created_date = (new DrupalDateTime())->setTimestamp($created_date);

    $this->day = $created_date->format('d');
    $this->month = $created_date->format('m');
  }

  /**
   * @return string
   */
  public function getDay(): string {
    return $this->day;
  }

  /**
   * @return string
   */
  public function getMonth(): string {
    return $this->month;
  }

}
