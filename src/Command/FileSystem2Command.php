<?php
namespace App\Command;

use DateTime;
use DateInterval;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\LockHandler;

/**
 * Hrajeme si s LockHandlerem a zkoušíme co ještě FileSystem umí
 * @author Jan Klat <jenik@klatys.cz>
 */
class FileSystem2Command extends Command
{

	protected function configure()
	{
		$this->setDescription("Hrajeme si s LockHandlerem a zkoušíme co ještě FileSystem umí")
			->setName("filesystem:2");
	}

	protected function execute(InputInterface $input, OutputInterface $outputInterface)
	{
		$lockHandler = new LockHandler($this->getName());
		if (!$lockHandler->lock()) {
			echo "Jiná instance commandu ještě běží!";
			return false;
		}

		$filesystem = new Filesystem();

		$files = [
			"http://placekitten.com/408/287",
			"http://placekitten.com/300/128",
			"http://placekitten.com/123/456",
			"http://placekitten.com/54/68",
			"http://foo.bar/123"
		];

		foreach ($files as $key => $file) {
			try {
				$targetDir = "tmp/".$key;
				$filesystem->mkdir($targetDir);
				$targetFile = $targetDir . "/" . $key . ".jpg";
				$outputInterface->write("kopíruji " . $file . " do " . $targetFile." - ");
				$filesystem->copy($file, $targetFile);
			} catch (IOException $e) {
				$outputInterface->writeln("Chyba ".$e->getMessage());
				continue;
			}
			$outputInterface->writeln("OK!");

			//Pro další příklad si ještě upravíme čas přístupu
			$accessDate = new DateTime();
			$accessDate->sub(new DateInterval("P".$key."D"));
			$filesystem->touch($targetFile, $accessDate->format("U"), $accessDate->format("U"));
		}
	}

}
