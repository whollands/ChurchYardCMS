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

define("Version", "1.0 Alpha");

$GLOBALS["Config"] = include("config/general.php");
// Assign config file to variable

//error handler function
function customError($errno, $errstr) 
{
	echo "Error Occurred";
    echo "<b>Error:</b> [$errno] $errstr";
    exit;
}

//set error handler
//set_error_handler("customError");

include("application/includes/Objects.php");
include("application/includes/Functions.php");
include("application/includes/Bootstrap-Elements.functions.php");
// include all objects, functions

session_start();

$db = new Database();

function GetPathPart($Part = 0)
{
	$Path = GetCurrentPath();

	if($GLOBALS["Config"]->URL->CleanURLs)
	{
		return $Path["call_parts"][$Part];
	}
	else
	{
		return $Path["call_parts"][$Part + 1];
	}
}

switch(GetPathPart(0))
{
	default: include("application/pages/website/view.php"); break;
	// return main website homepage

	case "": include("application/pages/website/view.php"); break;
	// return main website homepage


	case "ajax_request":
			$db = new Database();
			$Data = $db -> Select("SELECT GraveID, Type, Location FROM Graves ORDER BY GraveID");
			echo json_encode($Data);
	break;


	case "database":
		switch(GetPathPart(1))
		{
			default: include("application/pages/errors/404Error.php"); break;
			// return 404 error by default

			case "map":

			break;

			case "trees":

			break;

			case "search":

			break;

			case "view":

			break;
		}
	break;


	case "admin":

		$user = new User();
		$user -> IsLoggedIn();


		switch(GetPathPart(1))
		{
			default: include("application/pages/errors/404Error.php"); break;
			// return 404 error by default

			case "": header("Location: " . GetPageURL("admin/dashboard")); break;
			// redirect directory index to dashboard

			case "dashboard":  include("application/pages/admin/dashboard.php"); break;

			case "pages":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": include("application/pages/admin/pages/view.php"); break;
					case "new": include("application/pages/admin/pages/new.php"); break;
					case "edit": include("application/pages/admin/pages/edit.php"); break;
				}

			break;

			case "media":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": include("application/pages/admin/media/view.php"); break;
					case "upload": include("application/pages/admin/media/upload.php"); break;
					case "details": include("application/pages/admin/media/details.php"); break;
				}

			break;

			case "records":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": include("application/pages/admin/records/view.php"); break;
					case "new": include("application/pages/admin/records/new.php"); break;
					case "edit": include("application/pages/admin/records/edit.php"); break;
				}

			break;

			case "graves":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": include("application/pages/admin/graves/view.php"); break;
					case "new": include("application/pages/admin/graves/new.php"); break;
					case "edit": include("application/pages/admin/graves/edit.php"); break;
				}

			break;

			case "users":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": include("application/pages/admin/users/view.php"); break;
					case "new": include("application/pages/admin/users/new.php"); break;
					case "edit": include("application/pages/admin/users/edit.php"); break;
				}

			break;

			case "settings": include("application/pages/admin/settings.php"); break;
			case "jobs": include("application/pages/admin/jobs.php"); break;
			case "help": include("application/pages/admin/help.php"); break;

			case "profile":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": Redirect("admin/profile/edit"); break;
					case "edit": include("application/pages/admin/profile/edit.php"); break;
					case "change_password": include("application/pages/admin/profile/change_password.php"); break;
					case "sessions": include("application/pages/admin/profile/sessions.php"); break;
				}
			break;
		}
	// end of admin section
	break;

	case "login": include("application/pages/auth/login.php"); break;

	case "logout":
		$u = new User();
		$u -> Logout();
		Redirect();
	break;
}

