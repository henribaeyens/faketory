<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

use Configuration;
use Db;
use Faker\Factory;

abstract class AbstractProcessor implements ProcessorInterface
{
    protected $dbInstance;
    protected $faker;
    protected $anonymousCustomerId;
    protected $table;

    public function __construct(string $table)
    {
        $this->dbInstance = Db::getInstance(_PS_USE_SQL_SLAVE_);
        $this->faker = Factory::create('fr_FR');
        $this->anonymousCustomerId = (int) Configuration::get('PSGDPR_ANONYMOUS_CUSTOMER');
        $this->table = $table;
    }
    abstract protected function getBatch(int $batchSize, int $batchOffset): array;
    abstract protected function anonymize(array $objectIds): void;

}
