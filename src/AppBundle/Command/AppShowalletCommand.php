<?php

namespace AppBundle\Command;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppShowalletCommand extends ContainerAwareCommand
{
    const CURR_SOURCE = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param \AppBundle\Entity\Currency $from
     * @param \AppBundle\Entity\Currency $to
     * @param float $summ
     *
     * @return float
     */
    private function convert($from, $to, $summ)
    {
        if ($from == $to) {
            return $summ;
        }

        $result = $summ * $from->getValue() / $from->getNominal() / ($to->getValue() / $to->getNominal());

        return $result;
    }

    /**
     * Currency updates
     *
     */
    protected function checkCurrencyUpdates()
    {
        $file = self::CURR_SOURCE;
        $xml = simplexml_load_file($file);
        if ($xml === false) {
            throw new NotFoundHttpException('Can\'t update currencies courses');
        }

        $repository = $this->em->getRepository('AppBundle:Currency');

        foreach ($xml->children() as $child) {
            /**
             * @var $child \SimpleXMLElement
             */
            if ($child->getName() != 'Valute') {
                continue;
            }

            if (!($currency = $repository->findOneByNumCode(current($child->NumCode)))) {
                throw new EntityNotFoundException('Currency '.current($child->charCode).' does not exist.');
            }

            $currency->setNominal(current($child->Nominal));
            $currency->setValue(str_replace(',', '.', current($child->Value)));


            $this->em->persist($currency);
        }

        $this->em->flush();

    }

    protected function configure()
    {
        $this
            ->setName('app:showallet')
            ->setDescription('Show user wallet balance')
            ->addArgument('user', InputArgument::REQUIRED, 'User - wallet owner')
            ->addArgument('currency', InputArgument::OPTIONAL, 'Optional currency to convert');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user_name = $input->getArgument('user');
        $currency_code = $input->getArgument('currency');

        $user = $this->em->getRepository('AppBundle:User')->findOneByUsername($user_name);
        if (is_null($user)) {
            throw new EntityNotFoundException('The specified user does not exist.');
        }
        if (is_null($user->getWallet())) {
            $output->writeln('User has no money yet ((((');
            return;
        }

        $result = [];
        if (is_null($currency_code)) {
            foreach ($user->getWallet()->getTotals() as $item) {
                $result[] = $item->getCurrency()->getCharCode().': '.$item->getSumm();
            }
        } else {
            $currency = $this->em->getRepository('AppBundle:Currency')->findOneByCharCode($currency_code);
            if (is_null($currency)) {
                throw new EntityNotFoundException('The specified currency does not exist.');
            }
            $summ = 0;
            $now = new \DateTime();
            $now = $now->setTime(0, 0);
            foreach ($user->getWallet()->getTotals() as $item) {
                $from = $item->getCurrency();
                if ($from->getUpdatedAt() < $now || $currency->getUpdatedAt() < $now) {
                    $this->checkCurrencyUpdates();
                }
                $summ += $this->convert($from, $currency, $item->getSumm());
            }
            $summ = round($summ, 4);
            $result[] = $currency->getCharCode().': '.$summ;
        }

        $output->writeln($result);
    }
}
