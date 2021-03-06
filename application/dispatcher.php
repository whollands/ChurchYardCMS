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

Const Version = '1.0 Alpha';
// Current software version running

date_default_timezone_set('UTC');
// Set the timezone of the server

session_start();

$GLOBALS["Config"] = include("config/general.php");
// Assign config file to variable

include("application/includes/Functions.php");
include("application/includes/Objects.php");
// include all objects, functions

switch(GetPathPart(0))
{
	default: 
	case null:
		$Page = new Page();
		$Page->DisplayContent();
	break;
	// return main website homepage

	case "database":
		switch(GetPathPart(1))
		{
			default: Server::Error404(); break;
			// return 404 error by default

			case "": 
				Server::Redirect("database/view");
			break;

			case "view":
			    if(GetPathPart(2) == null)
				{
					IncludeScript("database/view_all_records.php");
				}
				else
				{
					IncludeScript("database/view_record.php");
				}
			break;

			case "map":
				IncludeScript("database/map.php");
			break;

			case "tree":
				IncludeScript("database/tree.php");
			break;
		}
	break;

	case "admin":

		User::CheckAuthenticated();

		switch(GetPathPart(1))
		{
			default: Server::Error404(); break;
			// return 404 error by default

			case "": Server::Redirect("admin/dashboard"); break;
			// redirect directory index to dashboard

			case "dashboard":  IncludeScript("admin/dashboard.php"); break;

			case "pages":
				switch(GetPathPart(2))
				{
					default: Server::Error404(); break;
					// return 404 error by default

					case "": IncludeScript("admin/pages/view.php"); break;
					case "new": IncludeScript("admin/pages/new.php"); break;
					case "edit": IncludeScript("admin/pages/edit.php"); break;
					case "delete":
						$PageID = GetPathPart(3);
						$Page = new Page();
						$Page->Delete($PageID);
						unset($Page);
						Server::Redirect("admin/pages");
					break;

				}

			break;

			case "media":
				switch(GetPathPart(2))
				{
					default: Server::Error404(); break;
					// return 404 error by default

					case "": IncludeScript("admin/media/view.php"); break;
					case "upload": IncludeScript("admin/media/upload.php"); break;
					case "details": IncludeScript("admin/media/details.php"); break;
					case "delete":
						$MediaID = GetPathPart(3);
						Media::DeleteMedia($MediaID);
						Server::Redirect('admin/media');
					break;
				}

			break;

			case "records":
				switch(GetPathPart(2))
				{
					default: Server::Error404(); break;
					// return 404 error by default

					case "": IncludeScript("admin/records/view.php"); break;
					case "new": IncludeScript("admin/records/new.php"); break;
					case "edit": IncludeScript("admin/records/edit.php"); break;
				}

			break;

			case "graves":
				switch(GetPathPart(2))
				{
					default: Server::Error404(); break;
					// return 404 error by default

					case "": IncludeScript("admin/graves/view.php"); break;
					case "new": IncludeScript("admin/graves/new.php"); break;
					case "edit": IncludeScript("admin/graves/edit.php"); break;
					case "delete": 
						$GraveID = GetPathPart(3);
						Grave::DeleteGrave($GraveID);
						Server::Redirect('admin/graves');
					break;
				}

			break;

			case "users":
				switch(GetPathPart(2))
				{
					default: Server::Error404(); break;
					// return 404 error by default

					case "": IncludeScript("admin/users/view.php"); break;
					case "new": IncludeScript("admin/users/new.php"); break;
					case "edit": IncludeScript("admin/users/edit.php"); break;
					case "delete":

						$UserID = GetPathPart(3);
						
						User::Delete($UserID);

						Server::Redirect("admin/users");

					break;
				}

			break;

			case "settings": IncludeScript("admin/settings.php"); break;
			case "jobs": IncludeScript("admin/jobs.php"); break;
			case "help": IncludeScript("admin/help.php"); break;

			case "profile":
				switch(GetPathPart(2))
				{
					default: Server::Error404(); break;
					// return 404 error by default

					case "": Server::Redirect("admin/profile/edit"); break;
					case "edit": IncludeScript("admin/profile/edit.php"); break;
					case "change_password": IncludeScript("admin/profile/change_password.php"); break;
					case "sessions": 
						switch(GetPathPart(3))
						{
							default: IncludeScript("admin/profile/sessions.php"); break;
							case "delete":
							$SessionID = GetPathPart(4);
							User::DeleteSession($SessionID);
							Server::Redirect("admin/profile/sessions");
							break;
						}
					break;
				}
			break;
		}
	// end of admin section
	break;

	case "login": 
		IncludeScript("auth/login.php");
	break;

	case "logout":
		User::Logout();
		Server::Redirect();
	break;
}