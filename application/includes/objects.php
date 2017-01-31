<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

/* ------------------------------------------------------------------------ /*

    Church Yard Content Management System
    Copyright (C) 2016 Will Hollands
    <http://hollands123.com/projects/churchyardcms/>

    For AQA NEA A-Level Project
    Designed for St. Peter's Church, Rendcomb.

    Released under GNU Public License
    ---------------------------------
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
    ---------------------------------

	For Help with Configuration please see
	<http://hollands123.com/projects/churchyardcms/support/config>

/* ------------------------------------------------------------------------ */

Class DatabaseConnection
{
	private $Username;
	private $Password;
	private $DatabaseName;
	private $Host;
	private $Port;
	private $Socket;
	private $Connection;

	function __construct()
	{
		$Config = include("config/database.php");
		// Encapsulate sensitive db info inside object.

		$this->Username = $Config->Username;
		$this->Password = $Config->Password;
		$this->DatabaseName = $Config->DatabaseName;
		$this->Host = $Config->Host;
		$this->Port = $Config->Port;
		$this->Socket = $Config->Socket;
		// Setup values in config file to $this object

		$this->Connect();
		// Create new database connection
	}

	function __destruct()
	{
		//$this->Disconnect();
		// @ Ignores all errors
	}

	private function Connect()
	{
		$this->Connection = @ new mysqli($this->Host, $this->Username, $this->Password, $this->DatabaseName, $this->Port, $this->Socket);
		// @ Ignores all errors

		if(mysqli_connect_errno($this->Connection))
		{
			include("application/pages/errors/DBConnectError.php");
			exit;
		}
	}

	public function Disconnect()
	{
		mysqli_close($this->Connection);
	}

	private function FilterQuery()
	{

	}

	function Query($SQL)
	{
		mysqli_query($this->Connection, $SQL)or die(mysqli_connect_errno($this->Connection));

		//$this->$Connection->query($SQL);
	}

	function Insert($SQL)
	{

	}

	function Update($Table, $Data, $Conditions)
	{
		// $SQL = "DELETE FROM $Table WHERE $Conditions";
		
		// mysqli_query($this->Connection, $SQL);
	}

	function Select($Table, $Fields, $Conditions = "")
	{	
	
		$SQL = "SELECT $Fields FROM $Table WHERE $Conditions";

		$Result = mysqli_query($this->Connection, $SQL);

		if($Result->num_rows == 0)
		{
			$Data = 0;
		}
		else
		{
			$Data = mysqli_fetch_array($Result);
		}

		return $Data;
	}

	function Delete($Table, $Conditions)
	{
		$SQL = "DELETE FROM $Table WHERE $Conditions";

		mysqli_query($this->Connection, $SQL);

	}

	function CountRows($Table, $Conditions)
	{
		$SQL = "SELECT * FROM $Table WHERE $Conditions";

		$Database = new DatabaseConnection();
		$Data = $Database->Select("Users", "", "$Conditions");
		$Database->Disconnect();

		return $Data->num_rows;
	}
}


Class User
{
	private $CookieName = "UserToken";
	// id of cookie that stores user's session

	function GetUserSalt($Username)
	{
		$Database = new DatabaseConnection();
		$Data = $Database->Select("Users", "Salt", "Username='$Username'");
		
		$Database->Disconnect();

		return $Data["Salt"];
	}

	function CheckCredentials($Username, $Password)
	{

		$UserSalt = $this->GetUserSalt($Username);

		$MasterSalt = $GLOBALS["Config"]->MasterSalt;

		$Password =  md5($MasterSalt . $Password . $UserSalt);

		// $Database = new DatabaseConnection();
		// $Data = $Database->CountRows("Users", "Username = '$Username' AND Password = '$Password'");
		// $Database->Disconnect();
		
		die($UserSalt);
		
		exit;
	}

	function ChangePassword($Username, $NewPassword)
	{
		$UserSalt = GetRandomToken();
		// generate a new random user salt

		$NewPassword = $GLOBALS["Config"]->MasterSalt . md5($NewPassword) . $UserSalt;
		// create a hash for the password

		$Database = new Database();
		$Database->Update("UPDATE Users SET Password = $NewPassword AND Salt = $UserSalt WHERE Username = $Username");
		// perform query to update database
	}

	function IsLoggedin()
	{
		$UserToken = $_COOKIE[$CookieName];

		$Database = new DatabaseConnection();
		$Database->CountRows("Sessions", "SessionID=''");
	}

	function Logout()
	{
		setcookie($CookieName, "", time() -3600);
		session_destroy();
	}
}

Class WebAddress
{
	// private $Base = $GLOBALS['Config']->URL->Base;
	//private $CleanURLs = $GLOBALS['Config']->URL->CleanURLs;

	function GetFullPath()
	{

	}

	function GetPageURL()
	{
		$URL = $Base . $File;

		if($CleanURLs)
		{
			$URL = $Base . "/index.php" . $File;
		}

		return $URL;
	}

	function GetResourceURL($File = "")
	{
		return $Base . $File;
	}
}

Class Jobs
{
	function GenerateSitemap()
	{

	}

	function GenerateRobots()
	// creates a robots.txt file
	{
		$Data = "Disallow: application/";
		$Data .= "\nDisallow: config/";
		$Data .= "\nDisallow: templates/";
	}

	function ClearCache()
	{
		foreach (new DirectoryIterator('cache/') as $fileInfo) 
		{
		    if(!$fileInfo->isDot())
		    {
		        unlink($fileInfo->getPathname());
		    }
		}
	}

	function IndexSearch()
	{

	}
}

Class Media
{
	function GetURLByID()
	{

	}

	function Upload()
	{

	}	

	function Delete()
	{

	}

	function Modify()
	{

	}
}

Class RecordSearch
{

}

Class Cache
{
	function CachePage()
	{

	}

	function CacheFamilyData()
	{

	}

	function CacheMap()
	{

	}
}

Class Map
{
	function GetGraveData()
	{

	}

}
