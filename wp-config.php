<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'marquesdaniel');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'danielmarquesd');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'pv43333');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'pZog.6ZP[rzo:Tq6QF8on!M]gKJ5?@6;g8tRzY8y)oEo|8&qjCC6C?$&SL/$KArt');
define('SECURE_AUTH_KEY',  'Hn5v@8rILdL#@W7YZ0t6vT~m>CgYcekawI8cX/ ,Y!Y}n9FVso9R*INNt(~/O&X.');
define('LOGGED_IN_KEY',    'HY0#F]O2y2njy*c@FC.)l;@!EYZ[2XoKv,DIBAj+wOqNXpoBp3v&dtiI[Y#6A^U^');
define('NONCE_KEY',        '`yyOKK:^3]3g<8>{D*wOp97^ZXY:[}HWXUZezT&EXD-upT>r GTESU|@3]aUn+v.');
define('AUTH_SALT',        'mgu8w8w*dzUWu]J.APBNSfA9!5czJ[!fML~5zzRCbt]H0MtrzJ$kFZ|1mYGFpo {');
define('SECURE_AUTH_SALT', 'pwhIxlcEy*z*?a}XQBZ=gk6;rd/!0qp~QCgm6Gj0#7813H(?E=QP%6>bDY%I?Bt:');
define('LOGGED_IN_SALT',   'E7nsCZu^.Wz_NiYw>-#o{*n=#}?&y!GjsdXElyMDkuc@ZM];z(WsKF.M68%bId}]');
define('NONCE_SALT',       '%$j08.11,Z6|xt~4`(X|.?<xEeCRuAYpXr-6dD`]O9y8(6z8wS%lM{tvYf3ZYbIO');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'pv_zqcfertukso_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');