<?php

namespace SintegraPHP\SC;

use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * SintegraSC
 *
 * @author Jansen Felipe <jansen.felipe@gmail.com>
 * @author Thiago Cordeiro <thiagoguetten@gmail.com>
 */
class SintegraSC
{
	public static $KEYMAP = [
		'CPF/CNPJ:' => 'cnpj',
		'Inscrição Estadual:' => 'inscricao_estadual',
		'Nome/Razão Estadual:' => 'razao_social',
		'Logradouro:' => 'logradouro',
		'Número:' => 'numero',
		'Complemento:' => 'complemento',
		'Bairro:' => 'bairro',
		'UF:' => 'uf',
		'Município:' => 'municipio',
		'CEP:' => 'cep',
		'Endereço Eletrônico:' => 'email',
		'Telefone:' => 'telefone',
		'Data de Início de Atividade:' => 'data_inicio_atividade',
		'Situação Cadastral Atual:' => 'situacao_cadastral',
		'Data desta Situação Cadastral:' => 'data_situacao_cadastral',
		'Observações:' => 'observacoes',
		'Regime de Apuração de ICMS:' => 'regimo_icms',
		'Enquadramento Fiscal:' => 'enquadramento_fiscal',
		'Código e Descrição da Atividade Econômica Principal :' => 'cnae_principal',
		'Contribuinte credenciado a emitir os seguintes documentos eletrônicos abaixo:' => 'contribuinte_credenciado_emitir',
		'Código e Descrição das Atividades Econômicas Secundárias :' => 'cnae_secundario',
	];

	/**
	 * Metodo para capturar o captcha para enviar no método de consulta
	 *
	 * @throws Exception
	 * @return array Captcha
	 */
	public static function getParams()
	{
		$client = new Client();
		$crawler = $client->request('GET', 'http://sistemas3.sef.sc.gov.br/sintegra/consulta_empresa_pesquisa.aspx');
		$urlCaptcha = $crawler->filter('#UpdatePanel1 img')->eq(0)->attr('src');

		$ch = curl_init("http://sistemas3.sef.sc.gov.br/sintegra/{$urlCaptcha}");
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_BINARYTRANSFER => TRUE
		);
		curl_setopt_array($ch, $options);
		$img = curl_exec($ch);
		curl_close($ch);

		return array(
			'captchaBase64' => 'data:image/png;base64,' . base64_encode($img),
			'viewstate' => $crawler->filter('#__VIEWSTATE')->attr('value'),
			'eventvalidation' => $crawler->filter('#__EVENTVALIDATION')->attr('value'),
			'viewstategenerator' => $crawler->filter('#__VIEWSTATEGENERATOR')->attr('value')
		);
	}

	/**
	 * Metodo para realizar a consulta
	 *
	 * @param  string $cnpj CNPJ
	 * @param  string $captcha CAPTCHA
	 * @param  string $challenge CHALLENGE
	 * @throws Exception
	 * @return array  Dados da empresa
	 */
	public static function consulta($cnpj, $captcha, $viewstate, $viewstategenerator, $eventvalidation)
	{
		$client = new Client();

		$param = array(
			'__VIEWSTATE' => $viewstate,
			'__VIEWSTATEGENERATOR' => $viewstategenerator,
			'__EVENTVALIDATION' => $eventvalidation,
			'__EVENTTARGET' => '',
			'__EVENTARGUMENT' => '',
			'opt_pessoa' => '2',
			'txt_CPFCNPJ' => $cnpj,
			'txt_IE' => '',
			'txtCodigoCaptcha' => $captcha,
			'btnEnviar' => 'Pesquisar'
		);

		$crawler = $client->request('POST', 'http://sistemas3.sef.sc.gov.br/sintegra/consulta_empresa_pesquisa.aspx', $param);

		return self::parser($crawler);
	}

	/**
	 * Metodo para efetuar o parser
	 *
	 * @param  Crawler $html HTML
	 * @return array  Dados da empresa
	 */
	public static function parser(Crawler $crawler)
	{

		$tdList = $crawler->filter('form td');

		$keys = [];
		$values = [];

		$i = 0;
		foreach ($tdList->filter('td') as $td) {
			$td = new Crawler($td);

			if ($td->filter('font:nth-child(1)')->count() > 0) {
				$content = strip_tags(trim(preg_replace('/\s+/', ' ', $td->filter('font:nth-child(1)')->html())));

				if ($td->attr('bgcolor') == '#f1f1b1') {
					$i++;
					$keys[$i] = trim($content);
				} else if ($td->attr('bgcolor') == '#fafae4') {
					$values[$i][] = trim($content);
				}
			}
		}

		$result = [];
		foreach ($keys as $k => $content) {
			$nkey = self::$KEYMAP[$content];
			if (count($values[$k]) > 1) {
				$result[$nkey] = $values[$k];
			} else {
				$result[$nkey] = $values[$k][0];
			}
		}



		return $result;
	}

}