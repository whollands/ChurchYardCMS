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
	default: include("application/pages/errors/404Error.php"); break;
	// return 404 error by default

	case "": include("application/pages/website/view.php"); break;
	// return 404 error by default

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

			case "editor":
				switch(GetPathPart(2))
				{
					default: include("application/pages/errors/404Error.php"); break;
					// return 404 error by default

					case "": include("application/pages/admin/editor/view.php"); break;
					case "new": include("application/pages/admin/editor/new.php"); break;
					case "edit": include("application/pages/admin/editor/edit.php"); break;

				}

			break;

			case "media": include("application/pages/admin/media.php"); break;

			case "records": include("application/pages/admin/records.php"); break;
			case "relationships": include("application/pages/admin/relationships.php"); break;

			case "settings": include("application/pages/admin/settings.php"); break;
			case "jobs": include("application/pages/admin/jobs.php"); break;
			case "help": include("application/pages/admin/help.php"); break;
		}
	// end of admin section
	break;

	case "login": include("application/pages/admin/authentication/login.php"); break;

	case "logout":
		$Session = new User();
		$Session->Logout();
		header("Location: " . GetPageURL());
	break;
}

