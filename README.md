# Rastro - PHP API
*Retorna dados de rastreamento de objetos nos correios por meio do código de rastreio com saídas em JSON ou XML.*

###### Exemplo funcional:
> http://taleslunadev.com/rastro/xml/JGXXXXXXXXXBR

------------------------------------------

###### Saída JSON:
  ``` JSON
{
	"DMXXXXXXXXXBR":[
		{
			"data":"08\/10\/2015 15:30",
			"local":" AC VIA SHOPPING BARREIRO - Belo Horizonte\/MG",
			"situacao":" Postado"
		},
		{
			"data":"08\/10\/2015 17:29",
			"local":" AC VIA SHOPPING BARREIRO - Belo Horizonte\/MG",
			"situacao":"Encaminhado para UNIDADE DE CORREIOS\/BR"
		},
		{
			"data":"09\/10\/2015 11:54",
			"local":" CTE BELO HORIZONTE - BELO HORIZONTE\/MG",
			"situacao":"Saiu para entrega ao destinat\u00e1rio"
		}
	  ]
  }
  ```

------------------------------------------

###### Saída XML:
  ``` XML
  <?xml version="1.0" encoding="UTF-8"?>
    <rastreamento cod='JGXXXXXXXXXBR'>
	    <rastreio>
		    <data>28/09/2015 17:44</data>
		    <local> CDD INTENDENTE CAMARA - Ipatinga/MG</local>
		    <situacao> Entrega Efetuada</situacao>
	    </rastreio>
    </rastreamento>
  ```

------------------------------------------

###### Arquivos:
  - Parse.class.php
    >Classe que realiza parse do HTML e cria um array com os dados
  
  - Xml.class.php
    >Classe que gera saída em XML
  
  - Json.class.php
    >Classe que gera saída em JSON
  
  - Index.php
    >Recebe as intruções e realiza
  
  - web.conf
    >Configurações para URL amigável em IIS
  
  - .htaccess
    >Configurações para URL amigável em Apache
