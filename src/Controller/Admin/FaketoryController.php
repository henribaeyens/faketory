<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use PrestaShop\Module\Faketory\Processor\ProcessorFactory;
use PrestaShop\Module\Faketory\Exception\EmptyTableException;
use PrestaShop\Module\Faketory\Exception\TableNotFoundException;
use PrestaShop\Module\Faketory\Exception\ProcessorNotFoundException;
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

    public function anonymizeAction(): JsonResponse
    {

        if (getenv('kernel.environment') === 'prod') {
            return new JsonResponse(
                [
                    'status' => 'danger',
                    'msg' => 'Not allowed to process on a production environment'
                ],
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        set_time_limit(600);
        ini_set('memory_limit', '512M');

        $processors = [
            'customer' => [
                'name' => 'customer',
                'pKey' => 'id_customer',
                'label' => 'Customers: ',
                'message' => '%d row(s) anonymized'
            ],
            'address' => [
                'name' => 'address',
                'pKey' => 'id_address',
                'label' => 'Addresses: ',
                'message' => '%d row(s) anonymized'
            ],
            'email_subscription' => [
                'name' => 'emailsubscription',
                'pKey' => 'id',
                'label' => 'Subscriptions: ',
                'message' => '%d row(s) anonymized'
            ],
            'mail_alert' => [
                'name' => 'mailalert_customer_oos',
                'pKey' => 'id_customer',
                'label' => 'Mail alerts: ',
                'message' => '%d row(s) anonymized'
            ]
        ];

        $responseData = [];
        foreach ($processors as $processor => $table) {
            try {
                $tableProcessor = $this->processorFactory->create($processor, $table['name'], $table['pKey']);
                $count = $tableProcessor->process();
                $responseData[] = [
                    'status' => 'success',
                    'msg' => $table['label'] . sprintf($table['message'], $count)
                ];
            } catch (EmptyTableException $e) {
                $responseData[] = [
                    'status' => 'warning',
                    'msg' => $table['label'] . $e->getMessage()
                ];
            } catch (TableNotFoundException|ProcessorNotFoundException|\Exception $e) {
                $responseData[] = [
                    'status' => 'danger',
                    'msg' => $table['label'] . $e->getMessage()
                ];
            }
        }

        return new JsonResponse($responseData, Response::HTTP_OK);
    }

}