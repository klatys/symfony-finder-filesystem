<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Budeme Vyhledávat nad soubory
 * @author Jan Klat <jenik@klatys.cz>*
 */
class FinderCommand extends Command
{

	protected function configure()
	{
		$this->setDescription("Jednoduchý správce souborů z předchozích příkladů")
			->setName("finder")
			->addOption("max-kbytes", "b", InputOption::VALUE_OPTIONAL)
			->addOption("name", "m", InputOption::VALUE_OPTIONAL)
			->addOption("max-age", "a", InputOption::VALUE_OPTIONAL);
	}

	protected function execute(InputInterface $input, OutputInterface $outputInterface)
	{
		$finder = new Finder();

		$table = new Table($outputInterface);
		$table->setHeaders([
			"Název",
			"Velikost",
			"Datum modifikace",
		]);

		if ($input->getOption("max-kbytes")) {
			$finder->files()->size("< " . (int)$input->getOption("max-kbytes") . "K");
		}

		if ($input->getOption("name")) {
			$finder->name((string)$input->getOption("name"));
		}

		if ($input->getOption("max-age")) {
			$finder->date(">= -" . (int)$input->getOption("max-age") . " day");
		}

		foreach ($finder->in("tmp") as $key => $item) {
			/** @var SplFileInfo $item */
			$table->addRow(
				[
					$item->getRelativePathname(),
					$item->isDir() ? "---" : round(($item->getSize() / 1024), 1) . "kB",
					date("Y-m-d H:i:s", $item->getMTime()),
				]
			);
		}
		$table->render();
	}

}
