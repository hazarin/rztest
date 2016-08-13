<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Currency as Currency;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppInitcurrencyCommand extends ContainerAwareCommand
{
    const CURR_SOURCE = 'http://www.cbr.ru/scripts/XML_daily.asp';

    protected function configure()
    {
        $this
            ->setName('app:initcurrency')
            ->setDescription('Initial upload or update of currencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = self::CURR_SOURCE;
        $xml = simplexml_load_file($file);
        if ($xml === false) {
            throw new NotFoundHttpException('Can\'t get currencies');
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $repository = $em->getRepository('AppBundle:Currency');

        foreach ($xml->children() as $child) {
            /**
             * @var $child \SimpleXMLElement
             */
            if ($child->getName() != 'Valute') {
                continue;
            }

            $pr = $child->NumCode;

            if (!($currency = $repository->findOneByNumCode(current($child->NumCode)))) {
                $currency = new Currency();
                $currency->setNumCode(current($child->NumCode));
                $currency->setCharCode(current($child->CharCode));
                $currency->setName(current($child->Name));
            }

            $currency->setNominal(current($child->Nominal));
            $currency->setValue(str_replace(',', '.', current($child->Value)));

            $em->persist($currency);

        }

        $em->flush();

        $output->writeln('Currencies loaded.');
    }
}
