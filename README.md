# ![DPP|by PDS](https://cldup.com/YbN6rT2IVF.png) UK Parliamentary Broadcast Assistant

[For documentation see the GitHub Pages](https://danleedham.github.io/UK-Parliamentary-Stacker/)

* [Introduction](#introduction)
* [Installation](#installation)
   + [Prerequisites](#prerequisites)
   + [Deployment](#deployment)
   + [Example Installation using XAMPP](#example-installation-using-xampp)
* [Deployment](#deployment-1)
* [Built With](#built-with)
* [Authors](#authors)
* [License](#license)
* [Acknowledgments](#acknowledgments)

## Introduction
_'The Stacker'_ is a browser based application that aids broadcast and journalistic coverage of the UK Parliament by leveraging live data powered by Data.parliament (DDP). There is no database as part of the project as in previous incarnations of the stacker. 

The Stacker consists of four sections:
  - Member Search
  - Questions Stacker
  - Windups
  - Guess Who?

> The Member Search allows the user to search by name, constituency or individual position across both houses of parliament. Either stock or screenshot images are presented for each member along with details of the member. If the member has Twitter, an optional toggle presents their latest Tweets.

> Oral Questions Stacker displays the current days upcoming questions. The user selects the department and type of questions, then the application loads the questions in their balloted order. Members due to ask questions are referred to by navigating through the list.  Navigating brings up details of the question and a picture of the member, so the user can identify who will speak next quickly without having to search. Functionality exists to withdraw questions and set groups. If used live, there is an option to move onto the next questioner once the currently selected member has started speaking. .

> Windups generates lists of speakers in the current chosen session which is helpful for when the responding party thanks everyone who's taken part in the debate so far. Once the session has ended this simply displays a list of everyone who spoke during that debate. The user has the option to toggle between showing every speaker in the order they spoke or to remove instances of individuals speaking multiple times. By default the speakers are shown in alphabetical order of their Constituency for the House of Commons, however this can be changed to be in time order. 

> Guess Who? helps for narrowing down potential members by the user selecting options including party, gender, date joined since, department and committee membership. 

## Installation
### Prerequisites
The Stacker requires either a webserver or a computer with a web connection and [PHP v7+](http://www.php.net) enabled server to run. The application has been developed to work on both standard web servers and on personal computers using [XAMPP](https://www.apachefriends.org/index.html), which is an easy to install Apache distribution containing MariaDB, PHP and Perl. 

### Deployment
For installation on a web server running PHP simply copy the contents of `src` to a folder of your choice and direct a browser to that folder. 

The application can run on any screen size from 400px width, and has been developed with an __optimal resolution of 1920x1080__, used with the latest version of __Google Chrome__. Full-screen is suggested (F11 PC, Ctrl+Cmd+F on a Mac), but multiple windows can be run together. It's suggested if two windows are used, fullscreen mode is activated and use Ctrl+Tab to swap between them while keeping fullscreen.

## Built With
The stacker is built upon a number of open source projects:
- [Bootstrap] - Used to build responsive, mobile-first projects on the web with the world's most popular front-end component library (ver  v3.3.7 with amendments)
- [Bootcards] - A cards-based UI with dual-pane capability for mobile and desktop, built on top of Bootstrap (ver 1.1.2 with amendments)
- [Bootstrap Toggle] - Bootstrap Toggle is a highly flexible Bootstrap plugin that converts checkboxes into toggles (ver v2.2.0)
- [DPP] - Data.Parliament - platform that enables sharing of data within and outside of Parliament
- [Chosen] - A jQuery Plugin by Harvest to Tame Unwieldy Select Boxes (ver 1.7.0)
- [jQuery] - a lightweight, "write less, do more", JavaScript library (ver 2.1.1)

## Authors
* **Dan Leedham** - *Initial work* - [NEP / NaSTA UK](https://github.com/danleedham)

## License
This project is licensed under the MIT License - see the [LICENSE.md](docs/LICENSE.md) file for details. 

## Acknowledgments
A huge thank you to those at the [Parliamentary Digital Service](https://github.com/ukparliament) for all their help understanding the amazing work they've been doing to make lots of data from the UK Parliament available. 

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

   [Bootstrap]: <http://getbootstrap.com/>
   [Bootcards]: <http://bootcards.org/site/about.html>
   [Bootstrap Toggle]: <http://www.bootstraptoggle.com/>
   [DPP]: <http://www.data.parliament.uk/>
   [Chosen]: <https://harvesthq.github.io/chosen/>
   [jQuery]: <http://jquery.com>
   [XAMPP]: <https://www.apachefriends.org/index.html>      
