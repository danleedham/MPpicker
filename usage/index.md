# User Manual
- [Getting Started](#getting-started)
  * [Launching the Application](#launching-the-application)
  * [The Four application tools](#the-four-application-tools)
  * [External Data status check](#external-data-status-check)
- [Member Picker](#member-picker)
  * [Search parameters](#search-parameters)
  * [Constituency](#constituency)
  * [Position](#position)
  * [Output Data](#output-data)
- [Questions](#questions)
  * [House of Commons Questions](#house-of-commons-questions)
  * [Printing question lists](#printing-question-lists)
  * [Navigating Questions](#navigating-questions)
  * [House of Lords Questions & Lists](#house-of-lords-questions---lists)
- [Debate Windups](#debate-windups)
  * [Events](#events)
  * [Duplicate Speakers Option](#duplicate-speakers-option)
- [Guess Who?](#guess-who-)
  * [Section Inputs](#section-inputs)
  * [Sorting Results](#sorting-results)
  
# Getting Started
## Launching the Application

For Mac and PC the application can be started and new windows added using the desktop shortcuts which are bundled with the application, found in the `apps` folder. 

![Windows Desktop Icons](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage1.png "Windows Desktop Icons")

![Mac Desktop Icons](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage2.png "Mac Desktop Icon")

For installations without the desktop icons, the application can be started manually. If the application is being run in the cloud, the stacker does not need to be started locally. If the application is being run locally the easiest way to start it up is by opening the XAMPP application and clicking start. If the application has been installed correctly this should automatically get the application up and running.

To access the application manually open a web browser (Google Chrome suggested) and point the web browser to the location of the folder on the server where the application is installed. If the application is being run locally using XAMPP for a PC the address is http://localhost and if it is a Mac then the address will be displayed in the XAMPP Server General tab, such as http://192.168.64.3.

For other devices that do not support running a php server locally, the application must be run in the cloud. To access the application simply open the server location in the device of choice. 

## The Four application tools
![Application Landing Page](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage3.png "Application Landing Page")

The default landing page for the application displays the four main tools available within the application. Each of the main buttons leads user to that part of the application. 

Once the application is loaded to press F11 (Mac: ^+⌘+F) to enter full screen within the web browser. If the tool bar still shows untick View -> Always show toolbar in full screen. 

## External Data status check
Below the application links the status check area displays current status of external data connections that the application uses. Should any of these be red that means the application is having trouble accessing that dataset. The section of the application which uses that data may not perform as desired if it cannot successfully access the external APIs. 

If all 4 of these are red it is likely that the application device does not have an internet connection. 

The numbers shown within the links represent other data checks such as the total number of members currently sat in the House of Commons (650) and House of Lords (800). To check if the server is running the correct date, this is shown above the Question Stacker.

# Member Picker
![Typical view of the Member Picker](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage4.png "Typical view of the Member Picker")

The Member Picker is a live search function that pulls up a list of members who meet the criteria as it is entered by a text input. The user checks the search parameter between Name, Constituency and Position then toggles between the House of Commons or House of Lords. For the Commons, the option to return Stock Images or Screenshots is also available. 

The layout of the tool is designed like a contact book. As the search increases in complexity the number of available results will reduce on the left-hand side. Clicking one of the results will display more detailed information about that selected member. 

## Search parameters
### House Selection
The user selects between searching the House of Commons or House of Lords using the green / red toggle button. Selecting the house determines which part of the Parliamentary database is searched. For searching across both houses it is suggested that the Guess Who section is instead used. 

### Name
By default the tool will search by member Name. Once three or more characters are entered the tool will return a list of members that contain those letters within their name. For the Commons this searches for preferred names containing the value specified.

For more detailed information on how the search works see: [http://data.parliament.uk](http://data.parliament.uk/membersdataplatform/memberquery.aspx#personalinfotable)

![Example Name search of the House of Lords](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage5.png "Example Name search of the House of Lords")

## Constituency
When using search by Constituency as three or more characters are entered the tool will return a list of members representing matching constituencies. For the Commons this returns members who currently represent a constituency that contains the search term within the constituency name. For the House of Lords this searches across the Lords full name including title and location. 

![Example Constituency search of the Commons
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage6.png "Example Constituency search of the Commons")

## Position
When searching by position an additional dialogue box is presented allowing the user to search between Government, Opposition and All Parties. This is set to All Parties by default. As the search term is four or more letters long, each member who has a government, opposition or other party position that contains the letters in the search is returned. Where there are a large number of positions that match the result the performance of the search is reduced. 

![Example Position search of Government members who have Health in their position title](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage7.png "Example Position search of Government members who have Health in their position title")

## Output Data
### General Member Data
When a member is selected from the list of results, the right hand pane loads that members extended details. This data contains the following information if is it held by the Parliamentary Members' Names Data Platform:
* Full Name
* Party
* Constituency (Commons only)
* Start Date in the House
* Current notable positions, including committee memberships

### Member Images
When Stock image is chosen (set by default) and a member is loaded, their new official portrait is loaded if it exists. For members without this new portrait their older official photograph is returned. When Screenshot is selected the latest available screenshot of the member from parliamentlive.tv is loaded. For the House of Lords as the stock images available are of such poor quality their most recent screenshot is always loaded. 

For all screenshots a Load Next Image button is presented. If the current screenshot is not helpful or representative (such as a nice wide shot of the chamber) then clicking this button will load the preceding screenshot available. 

![Example of a member screenshot showing Load Next Image button](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage8.png "Example of a member screenshot showing Load Next Image button")

### Late Shift Data toggle
When the late shift data toggle is pressed additional information about the member is loaded. This data includes:
* Their list biographical interests 
* Place and Date of birth (age)
* All constituencies they have represented
* Any constituencies they’ve run for election in that they’ve not won
* Any previous government or opposition positions and committee memberships. 

![Example of Late shift data](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage9.png "Example of Late shift data")

### Twitter toggle
For members that have a registered Twitter account, a button is presented that allows the user to toggle the member’s Twitter feed. Upon pressing the button the last few tweets including retweets are presented. The tweets are interactive just like on Twitter so clicking a link such as the handle will take you to that page. 

![Anna Soubry's latest tweets](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage10.png "Anna Soubry's latest tweets")

# Questions
The questions tool is designed to pull data from Parliament which displays the members due to ask questions or speak in the forthcoming set of Questions or debate. The tool is split between the House of Commons (default) and House of Lords, which is available using the button Switch to House of Lords.

## House of Commons Questions
![Typical view of House of Commons Questions](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage11.png "Typical view of House of Commons Questions")

For the House of Commons the tool pulls the upcoming questions from the Oral Questions dataset from Data.Parliament. Questions are typically available once the computerized ballot has been taken for the date. The list of questions is presented as a list on the left hand side and the current question is presented with a large image of the MP on the right, with the text of the question presented below. 

The date is set to todays date but should the user wish to look forward or backward in time for other day’s questions they can do so by changing the date within the date input at the top of the input menu. 

### Selecting Departments
For a given date the available departments are listed within the Department dropdown box. To reload the list of departments change the date. The departments that have questions tabled for that date are then listed, along with the option of All Departments. Picking each question will then reload which type of questions are available. If like questions to the Prime Minister there are only substantive questions, topical will not be available. 

![Options for selecting question Departments](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage12.png "Options for selecting question Departments")

### Question Types
When a department is selected the options for the type of questions are loaded. Selecting the type of question will only return questions of that type when Load is clicked. If All Types is selected substantive questions will be presented first, followed by topical questions. 

![Options for selecting the question types](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage13.png "Options for selecting the question types")

By default Topical questions are sorted by listing questions from the government party first, and then grouping the opposition questions in their balloted order. This option is set in the section below. 

![Topical Questions shown split by Government and Opposition](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage14.png "Topical Questions shown split by Government and Opposition")

### Setting Grouped & withdrawn Questions 
Clicking on the Set Groups button displays a popup window that allows the setting of grouped and withdrawn questions. First select the department you wish to group or withdraw questions from.

For questions that are to be answered in a group, input the groups with spaces between each question number, one group per line. As only substantive questions can be grouped do not enter their question type:
``` 
4 16 17
9 13 14
```
For questions that have been unstarred / withdrawn on the day, enter these in the Withdrawn on the day input. Withdrawn questions entered as their type letter then the number of the question, with a space between each question to be withdrawn: 
```
s1 t2
```

Questions that are withdrawn from the list of question to be asked prior to the order paper being printed are marked within the database as ‘Withdrawn Without Notice’. Occasionally questions that should have been marked as Withdrawn Without Notice are not so they need to be marked manually. For a question that appears on the list but does not appear on the order paper, that question should be added to the Withdrawn Before Order Paper Printed input. They should be entered as their type letter then the number of the question, with a space between each question to be removed: 
``` 
s1 t2
```

![Grouped & Withdrawn Questions popup showing groups and withdrawn questions](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage15.png "Grouped & Withdrawn Questions popup showing groups and withdrawn questions")

## Printing question lists
![Chrome print dialogue showing question list
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage16.png "Chrome print dialogue showing question list")

Upon loading the questions page the user should select the departments and question types they wish to print. Once the list is the list they wish to print the user should click "Print List" on the right hand side (before any members have been selected) to present the list in a print-friendly format. Printing using the usual Chrome print dialogue allows you to scale by a percantage to fit the images onto a certain number of pages (in + More Settings). 

## Navigating Questions
Once the list of questions is generated is it possible to navigate between questions either by clicking on the question they desire in the list, clicking the <- Previous or Next -> buttons at the top of the member image, or using the keyboard.

Users can move between questions using the keyboard arrow keys, with the right-hand arrow moving on +1 questions and the left-hand arrow key stepping back -1 question. 

To the top right of the large member image is a button that allows switching between Topical and Substantive questions. If Topicals are currently loaded then the button will swap to the Substantive questions. If Substantive questions are currently loaded then the button with swap to the Topical questions. 

## House of Lords Questions & Lists
![House of Lords Questions speakers list next to House of Lords Whips Todays List
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage17.png "House of Lords Questions speakers list next to House of Lords Whips Todays List")

As there is no public database API for Lords questions these are presented differently to their Commons counterparts. To swap the Questions tool to show the Lords questions, click Switch to House of Lords once the questions tool first loads. 

The Lords section of the questions tool has the single Section: input. Choosing Questions will then load the Oral Questions for that day. Typically, the questions are available all day, before the Lords Whips Todays Lists has been released. If any Private Notice Questions are set to be asked on that day, they will also be listed.

As with the Commons questions, clicking the member’s line on the left-hand list will then load their profile on the right-hand side. Unlike the Commons, this profile will be the same as the Member Picker rather than the large image. 

### Lords Speakers Lists
![House of Lords Speakers List
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage18.png "House of Lords Speakers List")

Speakers lists are available once the Lord’s Whips have released their lists for the day. Should there be speakers list(s) the title of the list is presented and the user selects the list, then loads the speakers for that list. Should there be an additional list that does not have a title shown with the dropdown list, this will be titled Manual List Number followed by the number of the speaker’s list which has no title.  

The list that is generated allows the user to select a member of the House of Lords by clicking that list item. Once selected the right hand side of the application window will give more detail on that member, including the option to look through screenshots of that member.

# Debate Windups
![House of Lords debate windup
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage19.png "House of Lords debate windup")

The Windups section is used to display the list of members who have taken part in a particular debate or item of business up to the moment. This is very helpful for understanding who the Minister will be answering with the windup speach or for being used as a quick reference for constituencies as speakers mention other members who have so far taken part or intervened in the debate so far.   

## Events
![House of Lords debate windup
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage20.png "House of Lords debate windup")

To select which debate to windup the first input required is the Event. The Event is typically the House of Commons or House of Lords, but should other committees or proceedings be logged live their lists can be used too. The list of events is generated live from the available proceedings taken place in Parliament as covered by ParliamentLive.tv. 

Some Events may appear to be shown twice such as Westminster Hall. This is bceause these will have multiple sittings, such as an afternoon and morning session. No further information is provided in the live data so this cannot be presented. 

### Debate Section
![House of Lords debate windup Section
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage21.png "House of Lords debate windup Section")

Once the correct event is chosen the application then offers the user the items of business that have so far taken place in that proceeding. As more items (such as Questions, Debates or general business) are progressed through the more become available to select. Selecting an item will then present the list of members who spoke in that debate.

If an item of business is still ongoing the list will continue to grow as more members speak in a debate. 

## Duplicate Speakers Option 
![House of Commons debate windup showing removed duplicates
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage22.png "House of Commons debate windup showing removed duplicates")

By default a member will only be listed as having spoken ('once') as the application will remove any duplicate instances of that individual speaking. By toggling the Remove Duplicates / Keep Dupicates button this option will allow the user to keep any dupliate entries, such as when the Prime Minister answers questions their name will appear between every member asking a question.

![House of Commons debate windup showing kept duplicates
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage23.png "House of Commons debate windup showing kept duplicates")

# Guess Who?
![House of Lords debate windup
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage24.png "House of Lords debate windup")

Guess Who is used to present lists of members who meet a set of criteria. This could be all members of a committee, female cabinet members, cross-bench members of the House of Lords who have joined in the past two months or plenty of other sub-sets of MPs and Lords. 

## Section Inputs
![House of Lords debate windup
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage25.png "House of Lords debate windup")

The tool has a set of inputs which are mostly drop down boxes. By default most are not selected. The more boxes you set values for the fewer members will meet the criteria. For Committees and Departments the user can type the name of the criteria and select from the options presented.

Some of the inputs such as the House will then trigger reloading of some of the input boxes. For example, choosing the House of Lords will then only present committees which are joint committees or House of Lords only committess. 

Choosing a department and not selecting Government / Opposition will only present the members from the Governing party and the official opposition. Choosing most other options will still show members of third parties that hold shadow positions. 

![House of Lords debate windup
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage26.png "House of Lords debate windup")

![House of Lords debate windup
](https://github.com/danleedham/UK-Parliamentary-Stacker/raw/gh-pages/images/docimage27.png "House of Lords debate windup")

## Sorting Results
Results can be sorted by First Name, Last Name, Constitency (A-Z, Z-A), Date joined and age. Note: some members have not disclosed their dates of birth so they cannot all be sorted perfectly.
