<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/Editing_wp-config.php Modifier
 * wp-config.php} (en anglais). C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'objectifdzbdd');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'objectifdzbdd');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'mens2hue');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'mysql51-74.perso');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/f+b?254[g*0(v:u@4ve6Z`m|T`v |7#[ke2*}=7FL1;4H7L(|jov#&qYbu$,0s`');
define('SECURE_AUTH_KEY',  '#d0^AzYNQ~LZZc+w`$|+r. 1ni[YQ/9c(6?<):nI7 #r]sbBfmJ/J_u.q:+nI5))');
define('LOGGED_IN_KEY',    '6Z6r*QRP[|{GzOcu,|C*q))uG)$@`+f;Ie.|+p$Ve=dwY]qDP$*O1-uYW)SpX]v6');
define('NONCE_KEY',        'l}+^zn0=7|x|C6P@dX7kQ@eJ8Q`|MXfLluJodOeDx&!~e>Y<}?Ny4>=G9XvzR9:|');
define('AUTH_SALT',        '+2MO75=deSXI8A|X.g)yC=W>$leGt }Y:L5:H[+-*5vYtKK({|i-(4fE+bY|axF-');
define('SECURE_AUTH_SALT', 'I5*O|qAcjdq;LuYbFz(z%8[lSfB^//00*5WE%N_G7Ci}yiDl~+q?`+R)*U=v,vQ5');
define('LOGGED_IN_SALT',   'J^>`dI>m#wrRK`)phG5a%a=k[>LsF`tZ>t^W_|!QU2.v}J&zCRo-@MPf}M<~NdT?');
define('NONCE_SALT',       'C^:05e|cf}`eBx@bK4UA%6M&F-@!]m!5w]9Ccy!KKPX||/+&.HuymoYh^QvB0KQ-');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');