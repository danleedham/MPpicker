# UK Parliamentary Stacker
[![DPP|by PDS](https://cldup.com/u7xgYVBRwu.jpg)](http://www.data.parliament.uk/)
## Introduction
The Stacker is a browser based application that aids broadcast and journalistic coverage of Parliament by leveraging live data powered by Data.parliament (DDP). There is no database as part of the project as in previous incarnations, instead a minimal amount of bespoke data is stored in XML files. 

The Stacker consists of three sections:
  - The Member Stacker
  - Oral Questions
  - Guess Who?

> The Member Stacker allows the user to search by name or constituency across both houses of parliament. Either stock or screenshot images are presented for each member along with details of the member. 

> Oral Questions displays the current days upcoming questions. The user selects the department and type of questions and the application loads the questions in their ballot order. Members due to ask questions are referred to by navigating through the list.  This brings up details of the question and a picture of the member, so the user can identify who will speak next quickly without having to search.

> Guess Who? helps for narrowing down potential members by the user selecting options including party, gender and committee membership. 

## External Projects Ustalised
The stacker uses a number of open source projects to work:
* [Bootstrap] - Used to build responsive, mobile-first projects on the web with the world's most popular front-end component library.
* [Bootcards] - A cards-based UI with dual-pane capability for mobile and desktop, built on top of Bootstrap
* [Bootstrap Toggle] - Bootstrap Toggle is a highly flexible Bootstrap plugin that converts checkboxes into toggles
* [DPP] - Data.Parliament - platform that enables sharing of data within and outside of Parliament
* [Chosen] - A jQuery Plugin by Harvest to Tame Unwieldy Select Boxes
* [jQuery] - a lightweight, "write less, do more", JavaScript library
* [XAMPP] -  an easy to install Apache distribution containing MariaDB, PHP, and Perl. The XAMPP open source package has been set up to be incredibly easy to install and to use.

## Installation
The Stacker requires a computer (Windows 2008, 2012, Vista, 7, 8, 10 (Important: XP or 2003 not supported)) with a web connection and [PHP v5+](http://www.php.net) enabled server to run. The application has been developed to work on both standard cloud-based web servers and XAMPP which is an easy to install Apache distribution containing MariaDB, PHP and Perl. 

The application can run on any screen size from 400px width, and has been developed with an optimal resolution of 1920x1080, used with the latest version of Google Chrome. Full-screen is suggested, but multiple windows can be run together. 

### Example Installation using XAMPP
1. Download XAMPP for the chosen operating system from [https://www.apachefriends.org](https://www.apachefriends.org). 
2. Install XAMPP using the easy to use installer. It doens't really matter where you install it to.
3. Start the server

For Mac: 

	1. Ensure the following Services are running as a minimum:
    	1. Apache
    	2. ProFTPD
	2. In Network settings, enable a port forwarding rule of: `localhost:8080 -> 80 (Over SSH)`
	3. Note the IP address of the server in the General tab (Example: 192.168.64.2)
	
For PC:	

	1. Ensure the following Services are running as a minimum:
    	1. Apache
    	2. ProFTPD
	2. In Network settings, enable a port forwarding rule of: `localhost:8080 -> 80 (Over SSH)`
	3. Note the IP address of the server in the General tab (Example: 192.168.64.2)
	
4. In Volumes mount the main stack exported data volumes. Click Explore to open up the server files. 
5. Open up the htdocs folder and delete current files. Copy the entire distribution folder into htdocs.
6. Open the file /etc/php.ini and on line 553 replace `display_errors=On` with `display_errors=Off`
7. Within a Google Chrome browser open up the IP address of the server

## Static Data
### Screenshot Images 
As these images are not part of any external dataset they are taken manually, sorted and added to the project directory:
* A 'full-resolution' at 1920x1080 placed into the /images/screenshot directory
* A smaller 960x540 version in the /images/screenshot/thumbs/ directory. 
* Each of the images is named by the Members DodsId and is in jpg format with 60% quality. 
* In future there is a possibility of pulling the swearing in section of the video stream from each parliament by using the AV Live Logging dataset found at http://www.data.parliament.uk/dataset/avlivelogging.

### Beta Images 
At the time of development these images are not part of an official external dataset API so the fancy new portraits have been included in this release as they were at the time of building. The images come in three types:
* A 'high-resolution' image at 1920x1080 placed into the /images/stock/ directory at 80% quality
* A smaller square 500x500 Close Up version placed in the /images/stock/500 directory at 70% quality
* A thumbnail 240x240 version in the /images/stock/thumbs/ directory at 60% quality

### Static Data Files
__/betaimages.xml__
An XML file that has elements that define each Member's new Parliament Beta imageid so that the images can be pulled from the new API. It is XML so that once a full public dataset of the ImageID values is released the transition in code will be minimal. 
```xml
  <member>
    <KnownAs>Maria Eagle</KnownAs>
    <imageid>9ybWAYuq</imageid>
  </member>
```
__/template/colors.php__
A php file that declares an array of colors for each party. The colors defined by Data.Parliament are not consistent with colors expected for an application such as this. The key is the PartyID as used in data.parliament and the value is the HEX value of the color.
```php
	"4"	  =>   "#0087DC",
```

## To-Do
 - Make new portraits dynamic. There is an API that can be scraped but at the moment it takes about 2 seconds per image to load. 
 - Continue mobile site improvement

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

   [Bootstrap]: <http://getbootstrap.com/>
   [Bootcards]: <http://bootcards.org/site/about.html>
   [Bootstrap Toggle]: <http://www.bootstraptoggle.com/>
   [DPP]: <http://www.data.parliament.uk/>
   [Chosen]: <https://harvesthq.github.io/chosen/>
   [jQuery]: <http://jquery.com>
   [XAMPP]: <https://www.apachefriends.org/index.html>