<?php

namespace AppBundle\Command;

use AppBundle\Entity\Total;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityNotFoundException;

class AppMakepaymentCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:makepayment')
            ->setDescription('Make paymet for selected user')
            ->addArgument('user', InputArgument::REQUIRED, 'User for payment')
            ->addArgument('currency', InputArgument::REQUIRED, 'Currency of payment')
            ->addArgument('summ', InputArgument::REQUIRED, 'Summ of payment')
            ->addArgument('oper', InputArgument::OPTIONAL, 'Operation. "credit" for credit');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user_name = $input->getArgument('user');
        $currency_code = $input->getArgument('currency');
        $summ = (real)$input->getArgument('summ');

        if ($oper = $input->getArgument('oper')) {
            switch ($oper) {
                case 'credit':
                    $summ = -$summ;
            }
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneByUsername($user_name);
        $currency = $em->getRepository('AppBundle:Currency')->findOneByCharCode($currency_code);
        if (is_null($user)) {
            throw new EntityNotFoundException('The specified user does not exist.');
        }

        if (is_null($currency)) {
            throw new EntityNotFoundException('The specified currency does not exist.');
        }

        if (!($wallet = $user->getWallet())) {
            $wallet = new Wallet();
            $wallet->setUser($user);
        }

        if (!($total = $wallet->getTotalByCurrency($currency))) {
            $total = new Total();
            $total->setCurrency($currency);
            $total->setSumm(0);
            $wallet->addTotal($total);
            $em->persist($total);
        }

        $transaction = new Transaction();
        $transaction->setTotal($total);
        $transaction->setSumm($summ);

        $result = 'Payment success.';
        if ($total->getSumm() + $transaction->getSumm() < 0) {
            $transaction->setSumm(-$total->getSumm());
            $result = 'Summ on balance too small. Summ changed at '.$transaction->getSumm();
        }

        $total->setSumm($total->getSumm() + $transaction->getSumm());

        $em->persist($transaction);
        $em->flush();

        $output->writeln($result);
    }

}
