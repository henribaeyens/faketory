<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Controller\Admin;

use PrestaShop\Module\Faketory\Processor\ProcessorFactory;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class FaketoryController extends FrameworkBundleAdminController
{
    /**
     * @var ProcessorFactory
     */
    private $processorFactory;

    /**
     * @param ProcessorFactory $processorFactory
     */
    public function __construct(ProcessorFactory $processorFactory)
    {
        $this->processorFactory = $processorFactory;
        parent::__construct();
    }

    public function anonymizeAction()
    {
        set_time_limit(600);
        ini_set('memory_limit', '512M');

        $counts = [];

        foreach (['customer', 'address', 'emailsubscription', 'mailalert_customer_oos'] as $table) {
            $processor = $this->processorFactory->create($table);
            if (null !== $processor) {
                $counts[$table] = $processor->process();
            }
        }

        $outputVars =  [
            'tables' => [
                'customer' => ['label' => 'Customers', 'count' => $counts['customer']],
                'address' => ['label' => 'Addresses', 'count' => $counts['address']],
                'emailsubscription' => ['label' => 'Subscription emails', 'count' => $counts['emailsubscription']],
                'mailalert_customer_oos' => ['label' => 'Mail alert emails', 'count' => $counts['mailalert_customer_oos']],
            ]
        ];

        return $this->render('@Modules/faketory/views/done.html.twig', $outputVars);
    }

}