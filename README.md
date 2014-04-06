###############################################
Members Only Pages
Pre 0.1 proof of concept
###############################################

Developer
-----------------------------------------------
Nicolaas Francken [at] sunnysideup.co.nz

Requirements
-----------------------------------------------
SilverStripe 2.3

Documentation
-----------------------------------------------
This helps you create a section of "Members Only"
pages, by adding a special usergroups.  This is
ideal for setting up an intranet.
That is, not just anyone can access the pages.
You have to be either an admin OR a member of the
specified group.

Installation Instructions
-----------------------------------------------
1. Find out how to add modules to SS and add module as per usual.
2. copy configurations from this module's _config.php file
into mysite/_config.php file and edit settings as required.
NB. the idea is not to edit the module at all, but instead customise
it from your mysite folder, so that you can upgrade the module without redoing the settings.