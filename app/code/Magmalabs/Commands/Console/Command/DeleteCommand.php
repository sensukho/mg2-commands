<?php

/**
 * @category Magento2
 * @package  Magmalabs_Commands
 * @author   JC Gil <juancarlos.gil@magmalabs.io>
 */
namespace Magmalabs\Commands\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Sales\Model\Order;

/**
 * Class DeleteCommand
 */
class DeleteCommand extends Command
{
    /**
     * Order argument
     */
    const ID = 'id';

    /**
     * Delete all
     */
    const DELETE_ALL = 'delete-all';

    /**
     * Order argument
     */
    const ORDER = 'order';

    /**
     * Order argument
     */
    const PRODUCT = 'product';

    /**
     * Order argument
     */
    const CATEGORY = 'category';

    /**
     * Order
     *
     * @var Order
     */
    private $order;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     *
     */
    public function __construct(
        \Magento\Framework\App\State $state
    )
    {
        $state->setAreaCode('adminhtml');
        $objectManager                  = \Magento\Framework\App\ObjectManager::getInstance();
        $this->order                    = $objectManager->create('\Magento\Sales\Model\Order');
        $this->registry                 = $objectManager->create('\Magento\Framework\Registry');
        $this->orderCollectionFactory   = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\CollectionFactory');
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('magmalabs:delete')
            ->setDescription('Delete command (Orders/Products)')
            ->addArgument(
                self::ID,
                InputArgument::OPTIONAL,
                'Id'
            )
            ->addOption(
                self::ORDER, 'o',
                InputOption::VALUE_NONE,
                'Work with Orders'
            )
            ->addOption(
                self::PRODUCT, 'p',
                InputOption::VALUE_NONE,
                'Work with Products'
            )
            ->addOption(
                self::CATEGORY, 'c',
                InputOption::VALUE_NONE,
                'Work with Categories'
            )
            ->addOption(
                self::DELETE_ALL, 'a',
                InputOption::VALUE_NONE,
                'Delete all'
            )
        ;

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument(self::ID);
        $deleteAll = $input->getOption(self::DELETE_ALL);

        switch (true){
            case  (int)$input->getOption(self::ORDER):
                switch ((bool)$deleteAll){
                    case true:
                        $this->registry->register('isSecureArea','true');
                        $orderCollection = $this->orderCollectionFactory->create();
                        foreach ($orderCollection as $order) {
                            $order->delete();
                        }
                        $this->registry->unregister('isSecureArea');
                        $output->writeln('<info>All orders are deleted.</info>');
                        break;
                    case false:
                        $order = $this->order->load($id);
                        if(empty($order) || !$order->getId()){
                            $output->writeln('<info>Order with id ' . $id . ' does not exist.</info>');
                        }else{
                            $this->registry->register('isSecureArea','true');
                            $order->delete();
                            $this->registry->unregister('isSecureArea');
                            $output->writeln('<info>Order with id ' . $id . ' is deleted.</info>');
                        }
                        break;
                }
                break;
            case  (int)$input->getOption(self::PRODUCT):
                switch ((bool)$deleteAll){
                    case true:
                        $output->writeln('<info>All products are deleted.</info>');
                        break;
                    case false:
                        $output->writeln('<info>Product with id ' . $id . ' is deleted.</info>');
                        break;
                }
                break;
            case  (int)$input->getOption(self::CATEGORY):
                switch ((bool)$deleteAll){
                    case true:
                        $output->writeln('<info>All categories are deleted.</info>');
                        break;
                    case false:
                        $output->writeln('<info>Category with id ' . $id . ' is deleted.</info>');
                        break;
                }
                break;
        }
    }
}
