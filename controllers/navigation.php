<?php


class Controller_Navigation extends Controller
{

	function home()
	{
		$vars = $this->getSession()->get();

		if (count($vars) > 0)
		{
			$view = $this->getView();
	
			$view->display(__FUNCTION__);
		}
		else
			$this->login();
	}


	function swap()
	{
		$vars = $this->getSession()->get();
		$session = $this->getSession();
		
		$formvars = array('server', 'database', 'user', 'password', 'name');
		
		
		foreach ($formvars as $input)
		{
			$src = $session->get('source_'.$input);
			$dest = $session->get('dest_'.$input);
			
			$session->set('dest_'.$input, $src);
			$session->set('source_'.$input, $dest);
		}
		
		
		Request::redirect($session->get('LastPage'));
		exit;
		
	}

	
	
	function login()
	{
		$view = $this->getView();
		$vars = $this->getSession()->get();
		$credDB = getCredentialDB();
		
	
		$previousConnections = $credDB->returnAssocTable("SELECT * FROM credentials ORDER BY last_use DESC");
		
		
		if (is_array($previousConnections))
		{
			$previousConnectionsGood["- Choose One -"] = null;
			
			
			foreach ($previousConnections as $value)
			{
				$key = Arrays::removeEmptyElements( $value );
//				unset($key['password']);
			
				$name = isset($key['name']) ? $key['name'] : $key['user']." @ ".$key['server'];
			
				$previousConnectionsGood[$name] = json_encode($value);
			}
			
			$view->assign('previousConnectionsGood', $previousConnectionsGood);
		}
		else
		{
			$view->assign('previousConnectionsGood', array() );
		}
		
		
		if (is_array($vars))
			foreach ($vars as $key => $val)
				$view->assign($key, $val);


	
		$view->display(__FUNCTION__); 
	
	}

	function credentialsave()
	{
	
		$vars = $_POST;
				
		
		foreach ($vars as $key => $val)
		{
			$this->getSession()->set($key, $val);
		}
	
	
		$newSource = Arrays::whiteList($vars, 'source_name as name, source_server as server, source_user as user, source_password as password, source_database as database');
		$whereSource = Arrays::whiteList($vars, 'source_name as name');
		
		$newDest = Arrays::whiteList($vars, 'dest_name as name, dest_server as server, dest_user as user, dest_password as password, dest_database as database');
		$whereDest = Arrays::whiteList($vars, 'dest_name as name');
		

		$stampUpdates['times_used'] = "times_used + 1";
		$stampUpdates['last_use'] = "NOW()";
		
		
		$credDB = getCredentialDB();
		
		$credDB->upsert('credentials', $whereSource, $newSource, $stampUpdates);
		
		$credDB->upsert('credentials', $whereDest, $newDest, $stampUpdates);
		

		cache_clear();
	
		Request::redirect("?task=navigation.home");
	
	}
	
	
	function databaselist()
	{
		$dbside = Request::getVar('dbside');
		$server = Request::getVar($dbside."_server");
		$user = Request::getVar($dbside."_user");
		$pass = Request::getVar($dbside."_password");
		
		$session = $this->getSession();
		$preselection = $session->get($dbside.'_database');

		
		
		ob_start();
		
		$connection = new DBO_MySQLi($server, $user, $pass);
		$errors = $connection->connection->connect_error;
		
		ob_end_clean();
		
		
		
		if (empty($errors))
			$returns = $connection->returnColumn("SHOW DATABASES");
		else
			$returns = array();
		
		$vars = $connection->returnAssocRow("SHOW VARIABLES LIKE 'hostname'");

		
		$returns = array_flip($returns);
		$returns = Arrays::BlackList($returns, "performance_schema, mysql, information_schema");
		$returns = array_flip($returns);
			
		$obj = new stdclass;
		$obj->hostname = $vars['Value'];
		$obj->message = $vars;
		
		if ($returns)
			$obj->content = '<select name="'.$dbside.'_database">'.HtmlElement::SelectOptions($returns, $preselection, true).'</select>';
		else
			$obj->content = '(2) '.LibStrings::truncate($errors, 25);
		
		exit ( json_encode($obj) );
	}
	
	
	
	
	private function cmdescape($cmd)
	{
		return preg_replace('/\W/', '\\\$0', $cmd);
	}
	
	
	private function escapedSessionParameter($index)
	{
		$session = $this->getSession();
		return $this->cmdescape($session->get($index));
	}
	
	
	function backup()
	{
		$view = $this->getView();

		
		$destconnection = "-h".$this->escapedSessionParameter('dest_server')." -u".$this->escapedSessionParameter('dest_user')." -p".$this->escapedSessionParameter('dest_password');
		$sourceconnection = "-h".$this->escapedSessionParameter('source_server')." -u".$this->escapedSessionParameter('source_user')." -p".$this->escapedSessionParameter('source_password');
		
		$destdb = $this->escapedSessionParameter('dest_database');
		$sourcedb = $this->escapedSessionParameter('source_database');
		
		$command_dest = "mysqldump --routines $destconnection $destdb > ${destdb}_`date '+%Y%m%d_%H%M'`.sql";
		$command_source = "mysqldump --routines $sourceconnection $sourcedb > ${sourcedb}_`date '+%Y%m%d_%H%M'`.sql";
		
		
		$sourcedump = "${sourcedb}_temp.sql";
		
		$restoreCommands[] = "mysqldump --routines $sourceconnection $sourcedb > $sourcedump";
		$restoreCommands[] = "mysql $destconnection $destdb < $sourcedump";
		$restoreCommands[] = "rm $sourcedump";
		
		$restore_short = "mysqldump --routines $sourceconnection $sourcedb | mysql $destconnection $destdb";
		
		$credential_file_dest = "[client]
host=".$this->escapedSessionParameter('dest_server')."
user=".$this->escapedSessionParameter('dest_user')."
password=".$this->escapedSessionParameter('dest_password');
		
		$credential_file_source = "[client]
host=".$this->escapedSessionParameter('source_server')."
user=".$this->escapedSessionParameter('source_user')."
password=".$this->escapedSessionParameter('source_password');
		
		$view->assign('backup_command_dest', $command_dest);
		$view->assign('backup_command_source', $command_source);
		$view->assign('credential_file_dest', $credential_file_dest);
		$view->assign('credential_file_source', $credential_file_source);
		$view->assign('restore_command', implode("\n", $restoreCommands));
		$view->assign('restore_command_short', $restore_short);
		
		$view->display(__FUNCTION__);
	
	}
	
	
	
	


}