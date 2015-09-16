<?php

use SintegraPHP\SC\SintegraSC;
use Symfony\Component\DomCrawler\Crawler;

class SintegraSCTest extends PHPUnit_Framework_TestCase
{
	public function testGetParams()
	{
		$params = SintegraSC::getParams();


		$this->assertEquals(true, isset($params['captchaBase64']));
		$this->assertEquals(true, isset($params['viewstate']));
		$this->assertEquals(true, isset($params['eventvalidation']));
		$this->assertEquals(true, isset($params['viewstategenerator']));

	}

	public function testParser()
	{
		$crawler = new Crawler();
		$crawler->addHtmlContent(file_get_contents(__DIR__ . '/resposta.html'));

		$result = SintegraSC::parser($crawler);

		$this->assertEquals($result["cnpj"], "83646984003710");
		$this->assertEquals($result["inscricao_estadual"], "254239900");
		$this->assertEquals($result["razao_social"], "A. ANGELONI &amp; CIA LTDA");
		$this->assertEquals($result["logradouro"], "RUA: BRUSQUE");
		$this->assertEquals($result["numero"], "00358");
		$this->assertEquals($result["complemento"], "LOJA");
		$this->assertEquals($result["bairro"], "CENTRO");
		$this->assertEquals($result["uf"], "SC");
		$this->assertEquals($result["municipio"], "ITAJAÍ");
		$this->assertEquals($result["cep"], "88303000");
		$this->assertEquals($result["email"], "-");
		$this->assertEquals($result["telefone"], "-");
		$this->assertEquals($result["data_inicio_atividade"], "30/11/2001");
		$this->assertEquals($result["situacao_cadastral"], "ATIVO");
		$this->assertEquals($result["data_situacao_cadastral"], "30/11/2001");
		$this->assertEquals($result["observacoes"], "");
		$this->assertEquals($result["regimo_icms"], "NORMAL");
		$this->assertEquals($result["enquadramento_fiscal"], "NORMAL");
		$this->assertEquals($result["cnae_principal"], "4711302 - Comércio varejista de mercadorias em geral, com predominância de produtos alimentícios supermercados");
		$this->assertEquals($result["contribuinte_credenciado_emitir"][0], "- - Credenciado a Emitir Escrituração Fiscal Digital - EFD a partir de 01/01/2009");
		$this->assertEquals($result["contribuinte_credenciado_emitir"][1], "- - Credenciado a Emitir Nota Fiscal Eletrônica - NFe a partir de 01/10/2010");
		$this->assertEquals($result["cnae_secundario"], "- ****** -");
	}

}