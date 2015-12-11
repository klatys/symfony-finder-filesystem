<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * První pokus se Symfony komponentou Filesystem
 * @author Jan Klat <jenik@klatys.cz>
 */
class FileSystem1Command extends Command
{

	protected function configure()
	{
		$this->setDescription("První pokus se Symfony komponentou Filesystem")
			->setName("filesystem:1");
	}

	protected function execute(InputInterface $input, OutputInterface $outputInterface)
	{
		$filesystem = new Filesystem();
		//vytvoříme si soubor se kterým budeme pracovat
		$filesystem->dumpFile("foo.txt", "Příšerně žluťoučký kůň úpěl ďábelské ódy");
		//složku do které jej přesuneme
		$filesystem->mkdir("tmp/test");
		//a jdeme na to!
		$filesystem->copy("foo.txt", "tmp/test/foo.txt", true);

		echo "Prošlo to?\n";
		echo $filesystem->exists("tmp/test/foo.txt") ? "jo!" : ":(";
	}

}
