SKIPPIT.PL

Errors handling
Sorting
Bootstrap integration


------------------------------------------------------------------
STRUCTURE

USER PERMISSIONS AROUND TASKS:

OWNER:
-view
-edit
-flag
-delete

USER:






Index:
-only logging, registration later, same template

Panel:
-Logo at top, redirection to panel on right side link to user settings
-leftbar with active threads and create thread button
-under logo sorting tool, thread info(?)
-Task window
	-color theme for power
	-only title and content(Edit timestamp)
	-popup menu for editing and settings
-create task button: opens in popup

2 Tasks in a row for widescreen, 1 for narrow
hide threads for narrow view

For thread owners display settings

For temporary users don't show threads menu, no need

-----------------------------------------------------------------
DATABASE

DATABASE STRUCTURE:

Table:
user_data

Values:
user_id																						INT, AUTO INC, PRIMARY
user_email																					VARCHAR, 64			REQUIRED (thread id for temporary)
user_password -Password hash																VARCHAR, 256		REQUIRED
user_name																					VARCHAR, 64			REQUIRED
user_is_admin -VALUES: 0 for not admin, 1 for admin											TINYINT, 1			DEFAULT: 0, AUTO
user_last_active -Timestamp updated after avery single operation(including page refresh)	TIMESTAMP			DEFAULT: current timestamp, AUTO

Table:
thread_data

Values:
thread_id 																					INT, AUTO INC, PRIMARY
thread_owner_id 																			INT					REQUIRED
thread_name																					VARCHAR, 32			REQUIRED, (rules: 3-24)
thread_version -VALUES: 0-3; 0-Simple, 1-Pro												INT, 2				DEFAULT: 0, REQUIRED

Table:
task_data

Values:
task_id -PRIMARY KEY, auto increment														INT, AUTO INC, PRIMARY
task_thread_id -For which thread task was created											INT					AUTO
task_user_id -Id of author																	INT					AUTO
task_title																					VARCHAR, 127		REQUIRED (rules: 3-64)
task_content																				VARCHAR. 2024		REQUIRED (rules: 2024 after htmlentities, said to be 1900)
task_create_timestamp																		TIMESTAMP			DEFAULT: current timestamp, AUTO
task_edit_timestamp -Timestamp of last edit													TIMESTAMP			DEFAULT: current timestamp, REQUIRED on edit
task_power -Values 1-5																		INT, 4				DEFAULT: 1, REQUIRED
task_is_complete -Values: 0 is not complete, 1 is complete									TINYINT, 1 			DEFAULT: 0, REQUIRED when called
task_is_pinned -Values: 0 is not pinned, 1 is pinned										TINYINT, 1			DEFAULT: 0, REQUIRED when called

Table
connection_user_thread  -table where information about connection to thread and permissions for that thread are stored

Values:

connection_user_id -External user_data key													INT 				REQUIRED
connection_thread_id -External thread_data key												INT 				REQUIRED
connection_view_power -Selects which tasks user can browse									INT, 4				REQUIRED, DEFAULT: 5
connection_is_owner -Values: 0 for not owner 1 for owner									TINYINT, 1			DEFAULT: 0
connection_edit_permission -Values: 0 for no permission to edit tasks, 1 for permission		TINYINT, 1			DEFAULT: 0
connection_delete_permission -As above														TINYINY, 1			DEFAULT: 0
connection_create_power -Up to which priority user can create tasks -0 for no permission	INT, 4				DEFAULT: 0
connection_complete_permission -Values: 0 cannot change completed flag, 1 can change		TINYINT, 1			DEFAULT: 0


-----------------------------------------------------------------
SESSION VALUES

ON LOG IN:
user_name
user_id - main variable to check if user is logged in, also key in database
user_temporary_flag - is user account temporary (local for single thread) or regular
user_active_thread - stores id of an active thread / 0 if there are no threads


user_is_admin - ----NOT IN USE
user_last_active - ----NOT IN USE

ERRORS(remember to clear after shown)
error_register																			-> index.php
error_login																				-> index.php
error_create_thread																		-> panel.php
error_change_active_thread																-> panel.php
error_task_delete																		-> panel.php
error_create_task																		-> panel.php
error_thread_delete 																	-> settings.php

MESSAGE(remember to clear after use)


LIMITS

Temporary users per thread
Threads per user (owner): 10
Tasks per thread: 1024


-----------------------------------------------------------------
INPUT DATA

user_name - alnum 3-20 characters
user_password - 8-48 characters

thread_name - alnum 1-24 characters

-----------------------------------------------------------------
THOUGHTS

IMAGE FOR THREAD (max.1)
THINK ABOUT LIMITS

-Threads per user cap
-Tasks per thread cap

REMEMBER ERROR MESSAGES
MAYBE CREATE MESSAGING SYSTEM (one session variable should be enough)

MAKE RULES FOR TEMPORARY USERS
-temporary users have flag TEMPORARY in field email instead of true value
-disable creating threads
-disable control panel
-to create temporary user use register form with flag



Later maybe add discussion to tasks in pro version
Possible to make owner's view and personal view
Simplified and pro version

For simplified version:

Owner choices at creation menu:
-can users see all or own tasks
-can users edit own tasks
Owner choices at user add menu:
-Can edit own tasks

PANEL

OWNER/USER/TEMPORARY USER

OWNER VIEW:

threads, tasks, settings

USER VIEW:

threads, tasks

TEMPORARY USER VIEW:

tasks


-----------------------------------------------------------------
TASKS

-ADD DUE TO TIME AND DEPEDENCY
-RETHINK STRUCTURE: VIEWS(personal, owners - plus sorting)
-FIX HTML ENTITIES string length(double-check strlen)
-ADD TIMESTAMP ON EDIT FUNCTION
-CHANGE TEMPORARY FLAG TO thread_id so when thread is deleted its easier to delete temporary users
-ADD SETTINGS PAGE
-ADD ERROR PRINT FUNCTION(for ongoing errors)
-fix settings layout
-change links to contain IDs only
-sanitize all GET/POST data
-check for multiple user addition
-join thread accept for regular user
-add authors to tasks
-add edit to tasks
-CODE VERIFY
-update doc(new: favorite tab, new db value(fav))
-update doc: changed db structure, new files added, changed register, added new errors
-update doc: ignored users fields
-check SQL injection
-add special mode while ignoring self
-change names to ID's if not exposed


-FIX PRINTING THREADS (add favorite tab)								--DONE
-ADD TABS TO SETTINGS													--DONE
-ADD LINKS TO CHANGE THREAD												--DONE
-ADD TOS(but not really)												--DONE
-ADD ERROR PRINTING TO SETTINGS											--DONE
-ADD THREADS AND TASKS LIMITS!!											--DONE
-ADD RECAPTCHA TO REGISTER FORM											--DONE
-ADD THREAD DELETION													--DONE
-ADD MESSAGE SYSTEM TO PANEL											--DONE
-ADD TASK DELETION														--DONE
-ADD ICONS																--DONE
-SPLIT CSS files														--DONE
-ADD ERROR/MESSAGE DIALOG BOX											--DONE
-ADD RETURN VALUES AFRER LOGIN/REGISTER									--DONE
-HIDE ADD TASK BUTTON IF NO ACTIVE THREAD								--DONE
-ADD BOOTSTRAP 															--DONE
-ADD TASK CREATION MODULE 												--DONE
-ADD REGISTER OPTION													--DONE
-CREATE THREAD PRINT AND SWITCH MODULE									--DONE
-HANDLE ERRORS (create_thread.php)										--DONE
-CREATE LIST OF SESSION VARIABLES										--DONE			
-CREATE CREATE_THREAD PAGE												--DONE
-CREATE DATABASE DOCUMENTATION											--DONE
-CREATE DUMMY USER PANEL												--DONE
-CREATE TEMPORARY USER FLAG!!!(IMPORTANT) 								--RESOLVED
-REWRITE LOGIN OPTION TO WORK WITH NEW DATABASE LAYOUT 					--DONE
-CREATE DB STRUCTURE 													--DONE

Errors:

--Can create task if thread does not exists								--Fixed		
--Active thread is not reset after thread deletion						--Fixed
--Tasks are not deleted after thread deletion							--Fixed


-----------------------------------------------------------------
LOG

--0.24

-User delete module complete

--0.23

-Started work on notifications - blocking users

--0.22

-user activation module completed

--0.21

-changed Quick Access tab to show only favorites(threads)

--0.20

-Permisions for panel.php view task create/delete set

--0.19

-Working on access permissions

--0.18

-Added ToS
-Tabs in settings
-Minor layout update

--0.17

-Added reCaptcha
-Added thread deletion-module

--0.16

-Tested functionality and access
-Denied direct access to script files

--0.15

-Added function to clear confirmation box
-Minor layout changes

--0.14

Added delete task module
Added error handling for panel
Minor layout changes

--0.13

Added icons, mobile panel menu
Fixed some layout issues

--0.12

Minor layout adjustments
Added dialog box to index page for error and message display
Now after login/registration fail input values are returned

--0.11

Added index page layout, minor changes to clear the code

--0.10

Added thread creation, logout link to layout, page somewhat functional

--0.09

Started creating layout
Basic bootstrap integration

--0.08

Completed create_task module
Added task printing to panel

--0.07

Added registeration option

--0.06

Added switch active thread module

--0.05

Fixed creating threads
Added thread exception handling

--0.04

Added list of SESSION variables
Completed exceptions in login module

--0.03

Added creating threads module

--0.02

Modified login to work with new DB
Added name/email login option

--0.01

Created db template
