<?php

namespace Drupal\drupalversary\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\drupalversary\Exception\UserNotFoundException;
use Opdavies\Drupalorg\Entity\User;
use Opdavies\Drupalorg\Query\UserQuery;

/**
 * Locate and retrieve a Drupal.org user account from the API.
 */
class AccountRetriever {

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * AccountLocator constructor.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The Drupalversary cache.
   */
  public function __construct(CacheBackendInterface $cache) {
    $this->cache = $cache;
  }

  /**
   * Retrieve a Drupal.org user from a username.
   *
   * @param string $username
   *   The username to search for.
   *
   * @return \Opdavies\Drupalorg\Entity\User
   *   The Drupal.org user data.
   */
  public function byUsername(string $username): User {
    $key = "name:{$username}";

    if ($cached = $this->cache->get($key)) {
      return User::create($cached->data);
    }

    $data = (new UserQuery())
      ->setOptions(['query' => ['name' => $username]])
      ->execute()
      ->getContents()
      ->first();

    if ($data === NULL) {
      throw new UserNotFoundException("Username {$username} not found.");
    }
    else {
      $this->cache->set($key, $data);

      return User::create($data);
    }
  }

  public function byUid(int $uid) {
    $key = "uid:{$uid}";

    if ($cached = $this->cache->get($key)) {
      return User::create($cached->data);
    }

    $data = (new UserQuery())
      ->setOptions(['query' => ['uid' => $uid]])
      ->execute()
      ->getContents()
      ->first();

    $this->cache->set($key, $data);

    return User::create($data);
  }

}
