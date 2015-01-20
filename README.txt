Web UI for Serviio
Front End for Serviio

Requirements: HTTP server, PHP5 (with XML simple and cURL), JavaScript-enabled web browser.
Serviio 1.3.0 or higher required.

TODO:
- maybe some controls (start/stop) of the status refreshes
- better notification of when new version of Web UI and Serviio exist

CHANGELOG:
- 1.0 - 3/1/2012 - Release of new version
- 1.1 - 6/24/2012 - Updates to work with Serviio 1.0
- 1.2 - 8/12/2012 - Final release for Serviio 1.0 including 1.0.1
- 1.3 - 12/13/2012 - Added separate media browser URL in config.php
                   - Added Serviio License Key import on About page
                   - Added Serviidb.com support to add to Online Sources
                   - Small number of code enhancements
                   - Added new external library from datatables.net
                   - Added jQuery UI custom Aristo Theme
                   - Added refresh after exiting license upload dialog
                   - Added Image tab under Metadata tab
                   - Tested to work with Serviio 1.1
- 1.4b1 - 04/05/2013 - Various code enhancements
                     - Language selection in console tab now working (fixed wrong cookie path)
                     - Improved multilanguage support, added new language tags
                     - Updated German translation
                     - Page is reloaded to discard changes on reset button press
                     - Media browser link now accessess the bound interface IP
                     - Status tab lets you now chose the bound network interface (Serviio API 1.2)
                     - Transcoding settings now working again (Serviio API 1.2)
                     - Added new subtitle options (Serviio API 1.2)
                     - Added new remote options (Serviio API 1.2)
                     - Tested to work with Serviio 1.21
- 1.4b2 - 05/06/2013 - Fixed library changes not savable in free version
                     - Improved multilanguage support
                     - Updated German translation
                     - Resources added from ServiiDB now have the correct feed type
                     - Remote tab not visible in free version of Serviio
                     - Installed plugins are now shown as separate tab under Library
                     - Added tab to display Serviio log information via variable defined in config.php
- 1.4b3 - 06/10/2013 - Selected language is now taken from Serviio instead of cookie usage
                     - Code cleanup
                     - Re-activated check for compatible Serviio version
                     - Unified button behavior
                     - Minor bugfixes
- 1.4b4 - 07/28/2013 - For log files, latest events are now shown on top, warnings are highlighted in yellow, errors in red
- 1.5b1 - 07/31/2013 - Updated to work with Serviio 1.3 (Import/Export of online sources not yet implemented)
                     - Log file can now be browsed
                     - Retrieve metadata now clickable when adding new sources
                     - Switched metadata and delivery tab
                     - Updated display string for access levels
                     - ServiiDroid icons are now used
                     - Updated layout of online sources table (ServiiLink not yet working)
- 1.5 - 08/25/2013   - Implemented and activated functionality to import/export online resources
                     - Minor corrections and updates
                     - Updated language files
                     - Fixed parser errors in presentation and logs tab
                     - Fixed variable naming in presentation tab which lead to parser error during save
                     - ServiiLink icon click now displays ServiiLink
                     - Updated logfile cookie lifetime
- 1.5.1 - 09/02/2013 - Changed style of online sources table to implement scrollbar
                     - Changed file extension from .soe to .sob for online sources backup
                     - Corrected icon size in library tab
- 1.5.2 - 11/17/2013 - Status tab: Renderers are now selected by corresponding line instead of checkbox
                     - Status tab: Fixed renderer removal in free version of Serviio
                     - Delivery tab: Fixed display of number of used CPU cores
                     - Logs tab: Fixed refresh button (applies for free version of Serviio)
                     - Multilanguage updates
                     - Library tab: Fixed erroneous access restriction behaviour
                     - Library tab: Fixed visibility of feed expiry interval
- 1.5.3 - 01/06/2014 - Video now again preselected while adding new online sources
                     - Removed redundant code
                     - Rewritten major parts of code
                     - Added button to check stream URL while adding or editing online sources
                     - Fixed bug for not providing parameters for REST API call postAction
                     - Updated page icon
                     - Improved error handling and error information output
                     - Fixed metadata rescan and library refresh
                     - Fixed refresh button in status tab
                     - Names of online sources can now contain characters like ' < > &
                     - Updated to jQuery 1.9.1
                     - Updated datatables and ColVis to latest nightly builds
                     - Fix a bug where ocasionally a selected local folder cannot be added or a wrong selection dialog might appear
                     - Updated Serviio version check
                     - Added page reload after OS import and license upload
                     - Revised style of renderers, local and online sources table to implement scrollbar
                     - Changed license upload method
                     - Changed filename for exported online sources
                     - Minor other bug fixes
                     - Multilanguage updates
- 1.5.4 - 01/20/2014 - Corrected function name
                     - Changed dialog size for Serviidb
                     - Fixed resource type for OS
                     - Serviidb OS item are now enabled by default
                     - Fixed a bug where scrollbar is not clickable in certain tables in Chrome
                     - Selectable event is now canceled on iPhone switch on status tab
                     - Deleted obsolete code which interfered with iPhone switch
                     - Video now again preselected while adding new online sources
- 1.5.5 - 10/31/2014 - Removed redundant code
                     - Added button to check stream URL while adding or editing online sources
                     - Fixed bug for not providing parameters for REST API call postAction
                     - Updated page icon
                     - Improved error handling and error information output
                     - Fixed metadata rescan and library refresh
                     - Fixed refresh button in status tab
                     - Names of online sources can now contain characters like ' < > &
                     - Updated to jQuery 1.9.1
                     - Updated datatables and ColVis to latest nightly builds
                     - Fix a bug where occasionally a selected local folder cannot be added or a wrong selection dialog might appear
                     - Updated Serviio version check
                     - Added page reload after OS import and license upload
                     - Fixed license upload
                     - Multilanguage updates
                     - Preparations for Serviio API update
- 1.6 - 12/22/2014 - Updated to work with Serviio 1.5 (requires Serviio 1.5)

ANNOUNCEMENTS:
http://forum.serviio.org/viewtopic.php?f=17&t=12593