<?php
	Config::SetPluginDir('plugins');
	Config::SetPluginPath(PPath.DS.$configuration->getItem('PluginDir'));
	Config::SetControlPath(FPath.'Controller');
	Config::SetModelPath(FPath.DS.'Model');
	Config::SetViewPath(PPath.DS.'View');
	Config::SetViewTemplate('Default');
	Config::SetVersion('2.0 Beta');
	Config::SetRedir('ap');
	Config::SetModuleRedir('module');
	Config::SetModulesDir('modules');
?>