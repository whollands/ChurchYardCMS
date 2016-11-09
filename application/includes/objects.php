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
		@ $this->Disconnect();
	}

	private function Connect()
	{
		$Connection = @ new mysqli($this->Host, $this->Username, $this->Password, $this->DatabaseName, $this->Port, $this->Socket);
		// @ Ignores all errors

		if($Connection->connect_errno)
		{
			include("application/pages/errors/DBConnectError.php");
			exit;
		}
	}

	private function Disconnect()
	{
		$this->Connection->close();
	}

	function Insert($SQL)
	{

	}

	function Update($SQL)
	{

	}

	function Select($SQL)
	{
		
		return $Result;
	}

	function Delete($SQL)
	{

	}
}


Class User
{
	private $CookieName = "UserToken";
	// id of cookie that stores user's session

	private function GetUserSalt($Username)
	{
		$Database = new Database();
		$Result = $Database->Select("SELECT Salt FROM Users WHERE Username = $Username");

		return $Result;
		// extract salt attribute for username given
	}

	function CheckCredentials($Username, $Password)
	{
		$UserSalt = $this->GetUserSalt($Username);
		$MasterSalt = $GLOBALS["Config"]->MasterSalt;

		$Password =  $MasterSalt . md5($Password) . $UserSalt;

		$Database = new DatabaseConnection();
		$Result = $Database->Select("SELECT Name, Username FROM Users WHERE (Username = ? OR Email = ?) AND Password = ?");
		
		if($Result->num_rows == 1)
		{
			setcookie($CookieName, $SessionID, time() + (86400 * 30));
		}
		else
		{
			// die
		}
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
		if($CleanURLs)
		{
			return $Base . "/index.php" . $File;
		}
		else
		{
			return $Base . $File;
		}
	}

	function GetResourceURL($File = "")
	{
		return $Base . $File;
	}
}

Class RecordSearch
{

}

Class Jobs
{
	function GenerateSitemap()
	{

	}

	function GenerateRobots()
	{

	}

	function ClearCache()
	{

	}

	function IndexSearch()
	{

	}
}

Class Map
{
	function GetGraveData()
	{

	}

}
