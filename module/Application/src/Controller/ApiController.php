<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class ApiController extends AbstractActionController
{
  private EntityManager $entityManager;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  // /api/rates/latest
  public function latestAction()
  {
    $response = $this->getResponse();
    $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

    // using some raw query so i dont need to load any entities (faster in this scenario)
    $conn = $this->entityManager->getConnection();
    $sql = '
      SELECT r1.currency, r1.value, r1.fetchedAt, r1.icon, r1.listingCount
      FROM currency_rates r1
      INNER JOIN (
        SELECT currency, MAX(fetchedAt) AS latest
        FROM currency_rates
        GROUP BY currency
      ) r2
      ON r1.currency = r2.currency AND r1.fetchedAt = r2.latest
      WHERE r1.listingCount > 0 
      AND r1.league = "Mercenaries"
      ORDER BY r1.value DESC
    ';

    // prepare the statement
    $stmt = $conn->prepare($sql);

    // execute the query — returns a Result object in DBAL 3
    $result = $stmt->executeQuery();

    // fetch all rows as associative arrays
    $rates = $result->fetchAllAssociative();

    // optionally map for API output
    $rates = array_map(function($rate) {
      return [
        'currency' => $rate['currency'],
        'value' => (float)$rate['value'],
        'fetchedAt' => $rate['fetchedAt'],
        'icon' => $rate['icon'],
        'listingCount' => $rate['listingCount'],
      ];
    }, $rates);

    $payload = [
      'success' => true,
      'data'    => $rates,
      'error'   => null
    ];

    $response->setStatusCode(200);
    $response->setContent(json_encode($payload));
    return $response;
  }

  // /api/rates/history?currency=AlchemyOrb
  public function historyAction()
  {
    $response = $this->getResponse();
    $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

    $currency = $this->params()->fromQuery('currency', null);

    if (!$currency) {
      $payload = [
        'success' => false,
        'data'    => [],
        'error'   => 'Missing currency parameter'
      ];
      $response->setStatusCode(400);
      $response->setContent(json_encode($payload));
      return $response;
    }

    try {
      $query = $this->entityManager->createQuery(
        'SELECT r.currency, r.value, r.fetchedAt, r.icon
         FROM Application\Entity\CurrencyRate r
         WHERE r.currency = :currency 
         AND r.league = "Mercenaries"
         ORDER BY r.fetchedAt DESC'
      )->setParameter('currency', $currency);

      $rates = $query->getArrayResult();

      $rates = array_map(function ($rate) {
        return [
          'currency'  => $rate['currency'],
          'value'     => $rate['value'],
          'fetchedAt' => $rate['fetchedAt'] instanceof \DateTimeInterface
                          ? $rate['fetchedAt']->format('Y-m-d H:i:s')
                          : $rate['fetchedAt'],
          'icon'      => $rate['icon'],        
        ];
      }, $rates);

      $payload = [
        'success' => true,
        'data'    => $rates,
        'error'   => null
      ];

      $response->setStatusCode(200);

    } catch (\Throwable $e) {
      $payload = [
        'success' => false,
        'data'    => [],
        'error'   => $e->getMessage()
      ];
      $response->setStatusCode(500);
    }

    $response->setContent(json_encode($payload));
    return $response;
  }

}
