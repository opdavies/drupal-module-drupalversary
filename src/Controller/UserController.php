<?php

namespace Drupal\drupalversary\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drupalversary\Service\AccountRetriever;
use Drupal\drupalversary\Service\CreatedDateParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserController extends ControllerBase {

  /**
   * The account locator service.
   *
   * @var \Drupal\drupalversary\Service\AccountRetriever
   */
  private $accountRetriever;

  /**
   * The created date parser service.
   *
   * @var \Drupal\drupalversary\Service\CreatedDateParser
   */
  private $createdDateParser;

  /**
   * UserController constructor.
   *
   * @param \Drupal\drupalversary\Service\AccountRetriever $account_retriever
   *   The account locator service.
   */
  public function __construct(AccountRetriever $account_retriever, CreatedDateParser $created_date_parser) {
    $this->accountRetriever = $account_retriever;
    $this->createdDateParser = $created_date_parser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('drupalversary.account_retriever'),
      $container->get('drupalversary.created_date_parser')
    );
  }

  /**
   * Display information for a Drupal.org user by username.
   *
   * @param string $username
   *   The username to search for.
   *
   * @return array
   *   A render array.
   */
  public function byUsername(string $username): array {
    $user = $this->accountRetriever->byUsername($username);

    $date_parser = $this->createdDateParser
      ->setCreatedDate($user->get('created'));

    return [
      '#title' => t('When is :name’s Drupalversary?', [':name' => $user->get('name')]),
      '#markup' => vsprintf('%s - %s', [
        format_date($date_parser->getDate(), 'long'),
        $this->formatPlural($this->createdDateParser->getNumberOfYears(), '1 year!', '@count years!'),
      ]),
      '#suffix' => '<br>' . $this->getSuffix($date_parser),
    ];
  }

  private function getSuffix(CreatedDateParser $date_parser) {
    $days_to_go = $date_parser->getDaysToGo();

    if ($days_to_go == 0) {
      return $this->t('It’s your Drupalversary!!');
    }

    return $this->formatPlural($days_to_go, '1 day to go!', '@count days to go!');
  }

}
