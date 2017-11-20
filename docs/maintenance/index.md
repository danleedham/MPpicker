# Application Maintenance

# Static Data
By design the application takes as much data as it can from live datasets in order to ensure that the data it uses is bang up to date (and doesn't require manual updating). At the time of release some data has been manually supplied for various reasons. It is hoped that over time this data will be replaced by data available in public datasets. 

## Beta Images 
At the time of development the new portraits for MPs are part of Parliament's unfinished new API. The IDs for each member are yet to be static and the server isn't optimized for rendering large numbers of images on demand. As such the fancy new portraits have been included in this release, as they were at the time of building. Once the new API is static, the new member images will be pulled in on the fly, which will keep them up to date and add any new members should there be by-elections, or indeed another genreal election. 

The new portrait images come in three types:
- A 'high-resolution' image at 1000x667 placed into the `/images/stock/` directory at 80% quality
- A smaller square 500x500 Close Up version placed in the `/images/stock/500` directory at 70% quality
- A thumbnail 240x240 version in the `/images/stock/thumbs/` directory at 60% quality

## Static Data Files
__/template/betaimages.xml__
An XML file that has elements defining each Member's Beta imageid is stored with the application. It is XML so that once a full public dataset of the ImageID values is released the transition in code will be minimal. The application uses memberid (which is static) in order to pull the imageid, with the KnownAs provided at the time of rendering to help human readability. This KnownAs may change such as when a member gets married.

Each member with a new image has an element: 
```xml
  <member>
    <KnownAs>Maria Eagle</KnownAs>
    <memberid>483</memberid>
    <imageid>9ybWAYuq</imageid>
  </member>
```

__/template/colors.php__
A php file that declares an array of colors for each party. The colors defined by Data.Parliament are not consistent with colors expected for an application such as this. The key is the PartyID as used in data.parliament and the value is the HEX value of the color.
```php
	"4"	  =>   "#0087DC",
```

# Application Filesystem Hierarchy
The application is stored within the `src` folder of the repository. Within the folder the application is broken down into multiple elements split across multiple `.php`, `.js` and `.css` files. External  such as jQuery and Google Fonts are called from public CDNs.

## Top Level Folders
* `/css` contains the css design files
* `/favicons` contains image and style files for favicons
* `/fonts` glyphicons for symbols
* `/js` main javascript files for external libraries that run the application. Note at this stage most JavaScript for the application specific functionality is stored within the individual pages. In future releases the JavaScript functions will be consolidated and reside here.
* `/template` is the main folder for most of the dynamic data that is pulled from the top level tools

## Top Level Files
* `index.php` is the landing page for the application and also dispalys the result of networktests. Primerially static, the page does load some data from external php pages to display current numbers of members and once the page loads, javascript triggers the network tests. 
* `qs.php` is the page that is the front end for the Questions Stacker tool
* `search.php` is the page that is the front end for the Member Picker tool
* `who.php` is the page that is the front end for the Guess Who? tool
* `windups.php` is the page that is the front end for the Wind Ups tool

# General Includes
* `footer.php` This file generates the toolbar at the bottom of the application that allows for switching between the tools. 
* `headinc.php` This file is included at the top of each page that and contains links to css files, the fav icon data, Bootstrap Toggle, Font Awesome and Google Fonts. If one whishes to add further includes or libraries that will be available across all pages it is suggested it is added to this file if it should be added to the header. 

# Application PHP
The application uses PHP to preprocess the displayed HTML code, to call information from various datasets, to manipulate this data and then present the data in forms helpful for the user. The application is built for use with PHP7.

Once the top level php files are loaded certain elements are reloaded or replaced with updated data whenever the user calls a function. These part files are all stored within the `template` folder. 

## Member Picker Specific PHP
### livesearch.php
* `/template/livesearch.php`
* Queries the Members' Names Data Platform API with a query for either name, constituency or position
* Input variables
    * `searchby` - 3 type switch, string, expecting one of `name` `constituency` or `position`
    * `q` - the search term. Expecting a string
    * `house` - string, expecting `Commons` or `Lords`
    * `side` - for position queries which subset of members to search, string, expecting `opposition` `government` or `both`
    * `mselected` - which MP has been clicked or is currently showing in the search, integer
* Outputs
    * HTML code with bootcards CSS
    * Multiple `<a></a>` elements
    * Elements are all `class="list-group-item"`

### member.php
* `/template/member.php`
* Returns the detailed information about a single member
* Input Variables
    * `m` - integer, the memberid for pulling from the Members' Name Data Platform
    * `photos` - string, expecting `screenshot` or `Stock`
* Outputs
    * HTML code with bootcards CSS
    * Sits within `class="panel panel-default"`   
    * Member image either as Parliament beta portrait or most recent screenshot

## Question Specific PHP
### listquestions.php
* `template/listquestions.php`
* Returns a list of members who have questions for the set criteria
* Input Variables
    * `date` - expecting date in format: `yyyy-mm-dd`
    * `type` - string, expecting `all`, `Substantive` or `Topical`
    * `dept` - string, expecting name of Department as returned by Oral Questions API
    * `groups` - expecting encodeURI'd version of a text area input. Question numbers separated by spaces, with each group separated by linebreaks
    * `withdrawn` - expecting encodeURI'd version of a string. Question type+number 's1' 't1' separated by spaces
    * `withoutnotice`  - expecting encodeURI'd version of a string. Question type+number 's1' 't1' separated by spaces
    * `together` - string, expecting `together` or `dont`
    * `topicalsbyparty` -  string, expecting `byparty` or `dont`
* Outputs
    * HTML code with bootcards css
    * Multiple `<a></a>` elements
    * Elements are all `class="list-group-item`
       
### questioner.php
* `template/questioner.php`
* Returns the detailed information about a single question including the large version of the member asking the question's image
* Input Variables
    * `uin` - integer, the id of the question to be loaded from the Oral Questions API
    * `date` - date of the question asked, in format: `yyyy-mm-dd`
    * `photos` - string, expecting `screenshot` or `Stock`
    * `next` - integer, the id of the next question in the list
    * `prev` - integer, the id of the previous question in the list
* Outputs
    * HTML code with bootcards CSS
    * Sits within `class="panel panel-default"`
    * Member image either as Parliament beta portrait or most recent screenshot

## Windups PHP


## Guess Who Specific PHP


# Application Javascript
The application uses Javascript to dynamic HTML. Each tool has multiple inputs and buttons which trigger functions that use input variables to return relevant data to the user. `template/core.php` provides the core jQuery and Bootstrap Javascript and is included on all pages that run bootstrap for the UI. 


For Questions key functions are:
* `loadquestions(date,dept,type)`
* `load(num,date)` 
* `loadlords(id)`
* `loadlordsquestions`
* `loaddepts(date)`
* `loadtypes()`

For the Member Picker key functions are:
* `showResult(str)`
* `load(id)`
* `twitter(handle)`
* `loadextras()
* `anotherphoto(current,m)`

For Guess Who key functions are: 
* `loadresults()`


For Windups key functions are: 
* `loadmembers(eventid,section)` 
* `loadevents(date)`
* `checkformembers()`

# CSS
* `bootcards-desktop.min.css`
    * Main theme file from the Bootcards library
    * Enhances Bootstrap library and helps deliver the main UI as we know it
    * Contains most of the css customisations that have been applied
* `bootstrap-theme.min.css`, `bootstrap.min.css`, `bootstrap-toggle.min.css`
    * Core parts of Bootstrap engine and Bootstrap Toggle 
    * Provides css for responsiveness, buttons and all the generic bootstrappy coolness
    * Largly unchanged from versions bundled with the external library
* `print-overrides.css`
    * This is called when the user requests a print friendly version of an application page
    * It mainly removes chunks of the page useless for printing and changes the core style of the questions list

# To-Do
 - Make new portraits dynamic. There is an API that can be scraped but at the moment the data within it is not static