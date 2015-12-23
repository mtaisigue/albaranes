<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */
/*Carga todos los archivos requeridos*/
//Main Class FKORE
include_once(SYSTEM_PATH.'Freekore/fkore/fkore.class.php');

//Create url_relative HTTP constant
fkore::createUrlRelative(); 

//agregar librerias generales
fkore :: _use("Freekore/libs/*",".php");

//agregar clase FkException
fkore :: _use('Freekore/fkore/FkException.class.php');

//agregar clase AppController
fkore :: _use('Freekore/fkore/AppController.class.php');

//agregar interface de base de datos
fkore :: _use('Freekore/fkore/db/db_interface.php');

//agregar adaptadores de base de datos
fkore :: _use("Freekore/fkore/db/adapters/*",".php");

//agregar Active Record de base de datos
fkore :: _use("Freekore/fkore/db/db_record.class.php");
fkore :: _use("Freekore/fkore/ActiveRecord.class.php");

//agregar Db Base
fkore :: _use("Freekore/fkore/db/db.php");


//DEFINED VARS
fkore :: _use('app/config/defined/default.php');

//load the app configuration
fkore::load_configuration();


//LANGUAGE
fkore :: _use('app/locale/'.$GLOBALS['APP_LANGUAGE'].'/index.php',$end='',$requred = false);


//AutoLoad
fkore :: _use('app/config/autoload.php');
fkore::fk_autoload();



