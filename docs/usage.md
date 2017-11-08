## Application Usage
### Launching the Application
Web: Once the files are moved onto the web server point a Google Chrome browser to the URL or IP address of the server
PC: Once installed to load the Stacker simply double click `stacker.bat`
Mac: Once installed to start the Stacker use the Open Stacker application ensuring that the server address is correct

## Static Data
### Beta Images 
At the time of development these images are part of Parliament's unfinished new API. The IDs for each member are yet to be static and the server isn't optimized for rendering large numbers of images on demand. As such the fancy new portraits have been included in this release, as they were at the time of building. Once the new API is static, the new member images will be pulled in on the fly, which will keep them up to date and add any new members should there be by-elections, or indeed another genreal election. 

The images come in three types:
- A 'high-resolution' image at 1000x667 placed into the /images/stock/ directory at 80% quality
- A smaller square 500x500 Close Up version placed in the /images/stock/500 directory at 70% quality
- A thumbnail 240x240 version in the /images/stock/thumbs/ directory at 60% quality

### Static Data Files
__/template/betaimages.xml__
An XML file that has elements defining each Member's Beta imageid is stored with the application. It is XML so that once a full public dataset of the ImageID values is released the transition in code will be minimal. Each member with a new image has an element: 
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
 - Make new portraits dynamic. There is an API that can be scraped but at the moment the data within it is not static