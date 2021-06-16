<?php

declare(strict_types=1);

namespace App\Command;

use App\Coupon\Factory\CouponFactoryInterface;
use App\Coupon\Factory\ValueCouponFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CouponGenerateValueCommand extends Command
{
    protected static $defaultName = 'app:coupon:generate-value';
    protected static $defaultDescription = 'Generate a batch of value coupon codes';

    private CouponFactoryInterface $couponFactory;

    public function __construct(ValueCouponFactory $couponFactory)
    {
        parent::__construct();

        $this->couponFactory = $couponFactory;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('currency', InputArgument::REQUIRED, 'The discount currency code')
            ->addArgument('amount', InputArgument::REQUIRED, 'The discount amount (decimal format)')
            ->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Max number of coupons to generate', 1_000_000)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = \time();
        $limit = (int) $input->getOption('limit');

        $discount = \sprintf('%s %s', $input->getArgument('currency'), $input->getArgument('amount'));

        $coupons = [];
        for ($i = 1; $i <= $limit; ++$i) {
            $coupons[$i] = $this->couponFactory->createCoupon([
                'discount' => $discount,
            ]);
        }

        $io = new SymfonyStyle($input, $output);

        $io->table(
            ['Time (seconds)', 'Memory (Mb)'],
            [
                [\time() - $start, \memory_get_peak_usage() / (1024 * 1024)],
            ]
        );

        $io->success('Done');

        return Command::SUCCESS;
    }
}
