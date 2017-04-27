<?php
 /**
 *
 * @package     Wizard-Newsletter
 * @name        create.php
 * @version     1.0.0
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @author      Joao Paulo Bastos L. <jpbl.bastos at gmail dot com>
 * @date        22-Mar-2017
 * @description Criar campanha em html
 *
 */

/**
 * Variaveis da aplicação
 */

/**
* Diretorio raiz da aplicação
* @var string
*/
$raizDir=dirname( __FILE__ ) . DIRECTORY_SEPARATOR;

/**
* Diretorio de upload dos arquivos de configuração
* @var string
*/
$uploaddir = $raizDir.'configs/';

/**
* Layout Header
* @var string
*/
$layoutHeader=file_get_contents($raizDir.'layouts/header.html');

/**
* Layout Footer
* @var string
*/
$layoutFooter=file_get_contents($raizDir.'layouts/footer.html');

/**
* Layout post par 
* @var string
*/
$layoutPost=file_get_contents($raizDir.'layouts/post.html');


/**
 * Funçoes da aplicação
 */



/**
 * Start da aplicação
 */
$fileConfig   = $_FILES['configFile']; 
$nameNewFile  = time().'.inc';

/* Verifica extenção */
if ( $fileConfig['type'] != 'application/octet-stream') {
	echo "<h1> Arquivo de configuraçao ".$fileConfig['name']." invalido. Verifique sua extenção !</h1>\n";
    exit();
}

/* Verifica upload */
if (!move_uploaded_file($fileConfig['tmp_name'], $uploaddir.$nameNewFile )) {
    echo "<h1> Erro ao fazer upload do arquivo de configuraçao ".$fileConfig['name'].". Verifique permissões !</h1>\n";
    exit();
}

/* Faz include da configuração */
if ( is_file($uploaddir.$nameNewFile) ){
   include($uploaddir.$nameNewFile);
} else {
   echo "<h1> Erro ao acessar do arquivo de configuraçao ".$fileConfig['name'].". Verifique permissões !</h1>\n";
   exit();
}

/* Monta html */
$html='';

/* Header */
$html .= $layoutHeader;
$html  = str_replace('{newsLetter}',  htmlentities($CONFIGNL['newsLetter']), $html);
$html  = str_replace('{tituloCabelaho}',  htmlentities($CONFIGNL['tituloCabelaho']), $html);
$html  = str_replace('{corCabecalho}',  $CONFIGNL['corCabecalho'], $html);
$html  = str_replace('{mascoteImage}',  $CONFIGNL['mascoteImage'], $html);

/* Posts */
for ($i=1; $i <= $CONFIGNL['quantidadePost']; $i++) { 
	$html .= $layoutPost;
	if($i % 2 == 0){
       $html  = str_replace('{postBackground}',  'E4F7FA', $html);
	}else{
       $html  = str_replace('{postBackground}',  'FFFFFF', $html);
	}
	$idPost = 'post'.$i;
	$html  = str_replace('{tituloPost}',  htmlentities($CONFIGNL[$idPost]['tituloPost']), $html);
	$html  = str_replace('{iconePost}',  $CONFIGNL[$idPost]['iconePost'], $html);
	$html  = str_replace('{textoPost}',  htmlentities($CONFIGNL[$idPost]['textoPost']), $html);
	$html  = str_replace('{botaoPost}',  htmlentities($CONFIGNL[$idPost]['botaoPost']), $html);
	$html  = str_replace('{linkPost}',  $CONFIGNL[$idPost]['linkPost'], $html);
}

/* Footer */
$html .= $layoutFooter;
$html  = str_replace('{tituloRodape}',  htmlentities($CONFIGNL['tituloRodape']), $html);
$html  = str_replace('{conteudoRodape}',  htmlentities($CONFIGNL['conteudoRodape']), $html);
$html  = str_replace('{botaoRodape}',  htmlentities($CONFIGNL['botaoRodape']), $html);
$html  = str_replace('{linkRodape}',  $CONFIGNL['linkRodape'], $html);

echo $html;

?>