<?php

use SintegraPHP\SC\SintegraSC;
use Symfony\Component\DomCrawler\Crawler;

class SintegraSCTest extends PHPUnit_Framework_TestCase
{
	public function testGetParams()
	{
		$params = SintegraSC::getParams();
	}

	public function testParser()
	{
		$crawler = new Crawler();
		$crawler->addHtmlContent(file_get_contents(__DIR__ . '/resposta.html'));

		$result = SintegraSC::parser($crawler);
	}

}