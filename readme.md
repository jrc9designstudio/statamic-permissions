# Permissions for Statamic 2
*Requirement:* Statamic v2.x
*Version:* 0.0.1 Alpha

## Caution
This project has not been widley tested, and only works for sites have not been moved above the web root. Only use this if you have a good understanding of file permissions as this could break your entire website, if the permissions are set wrong.

### What is this?
A CLI tool to help correct & Secure file and folder permissions for Statamic

### Installation
- Rename the folder `Permissions` and copy it to your `site/addons` folder

### Usage
- Change the seetings in your enviroment file (samples found in sample.env)
- Use `sudo php please permissions:fix` from the command line.

### Notes
- `PERMISSIONS_LOCKED_USER` and `PERMISSIONS_LOCKED_GROUP` should propaly be set to the user that you use to edit / deploy code.
- Using the `PERMISSIONS_LOCK_STATAMIC` option will break the web updater, but some of us want to only be able to update Statamic via the CLI.
