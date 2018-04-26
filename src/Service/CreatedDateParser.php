<?php

namespace Drupal\drupalversary\Service;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\drupalversary\Model\DrupalversaryDate;

/**
 * Parse a user's created date and time, and compare to the current date.
 */
class CreatedDateParser {

  /**
   * The current date.
   *
   * @var int
   */
  private $currentDate;

  /**
   * The user created date.
   *
   * @var int
   */
  private $createdDate;

  /**
   * CreatedDateParser constructor.
   *
   * @param \Drupal\Component\Datetime\TimeInterface $current_time
   *   The datetime service.
   */
  public function __construct(TimeInterface $current_time) {
    $this->currentDate = $current_time->getCurrentTime();
  }

  /**
   * Set the user created date.
   *
   * @param string $date
   *   The date timestamp.
   *
   * @return \Drupal\drupalversary\Service\CreatedDateParser
   */
  public function setCreatedDate(string $date): CreatedDateParser {
    $this->createdDate = $date;

    return $this;
  }

  /**
   * Determine if the user created date matches the current date.
   *
   * @return bool
   *   TRUE if the created date matches the current date, FALSE if not.
   */
  public function isMatch(): bool {
    $created_day = date('d', $this->createdDate);
    $created_month = date('m', $this->createdDate);

    $current_day = date('d', $this->currentDate);
    $current_month = date('m', $this->currentDate);

    return $created_day == $current_day &&
      $created_month == $current_month;
  }

  public function getDate() {
    $date = new DrupalversaryDate($this->createdDate);
    $current_time = new DrupalDateTime();

    $next_year = $current_time->format('Y');
    if ($current_time->format('m') > $date->getMonth()) {
      $next_year++;
    }

    return (new DrupalDateTime())
      ->setDate($next_year, $date->getMonth(), $date->getDay())
      ->modify('midnight')
      ->getTimestamp();
  }

  public function getDaysToGo() {
    $drupalversary_date = $this->getDate();

    $current_date = (new DrupalDateTime())
      ->modify('midnight')
      ->getTimestamp();

    return ($drupalversary_date - $current_date) / (60 * 60 * 24);
  }

  public function getNumberOfYears() {
    $date = new DrupalversaryDate($this->createdDate);

    $start_year = (new DrupalDateTime())
      ->setTimestamp($this->createdDate)
      ->format('Y');

    $current_time = new DrupalDateTime();
    $current_year = $current_time->format('Y');
    if ($current_time->format('m') > $date->getMonth()) {
      $current_year++;
    }

    return $current_year - $start_year;
  }

}
