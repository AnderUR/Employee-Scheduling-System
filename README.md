# Employee Scheduling System (ESS)

## About

The original purpose of this project was to provide an easy solution for college assistants to sign in/out and remove paper usage at the New York City College of Technology’s Ursula Library. The ESS was heavily integrated with the internal web app used at the library. With multiple projects running in one CodeIgniter installation, I needed to do extensive cleanup and editing to allow this project to be open source. This will explain why some files may have just one function, or strange naming conventions, or why some functions may have error handling, while others do not, etc. Some of the ESS capabilities include creating employee shifts, real-time sign-in status, simple employee substitution process, adding anouncements, among others. For full capabilities, please see the attached [documentation](/docs/ESS_Help_Doc.pdf). I hope you are able to extend this project and make great usage of it.

## Prerequisite

1. Install a server, such as WAMP, or XAMP.
2. Download a copy of CodeIgniter into your servers' web folder.
3. Make sure you can access CodeIgniter in your browser.

## Installation And Deployment

1.	Rename the CodeIgniter folder to Libservices.
2.	Drop the ESS assets folder where your Libservices application folder is located.
3.	Place all ESS files and folders in their respective Libservices folders. 
    - Drop the files inside the ESS config folder into your Libservices/application/config folder, replace the files in destination, or edit your originals accordingly.
    - Drop the files inside the ESS controllers folder into your Libservices/application/controllers folder.
    - Drop the files inside the ESS language\english folder into your Libservices/language/english folder.
    - Drop the files inside the ESS library folder into your Libservices/application/libraries folder.
    - Drop the files inside the ESS models folder into your Libservices/application/models folder.
    - Drop the folders inside the ESS views folder into your Libservices/application/views folder.
4.	In the ESS sql folder, locate the sql file. The DEFINER for procedures is ‘root’@’localhost’ and you need to change it if you are not using a local server. You can change it by opening the file in your code editor and doing a search/replace accordingly. 
5.	Load the ESS sql file into your database, through the command line, MySQL Workbench or other, making sure to include both structure and data.
6.	Place the ESS docs folder wherever you can easily access them for reference.
7.	Head to 'yourDomain'/Libservices/index.php/auth/login to **login with barcode 12345 or email: ess@ess.com and password: password**. 
8.	Read the docs or use the Help menu located under the username at the top right to use the application.

#### Setup Email in The Application

Some functions in the ESS, such as creating a new user, will require a Gmail email to be setup. 

1.	In Application/config folder, change smtp_user and smtp_pass in the emai.php file for emailing to work. The Gmail account used must be allowed smtp requests, although I noticed that creating a new account didn’t create such issue; you may want to do that for testing purposes.
2.	Also in the config folder, change the ion_auth file’s  admin_email to whichever email you want, which will be used for the reply-to function in Gmail.
3.  To modify the email content, search for $emailData['title'] in the project and you will find a few instances of that together with the other required pieces
    - To show the logo picture, or change it, modify the url in $emailData['titleImgSrc'] to a publicly accessible picture. 
    - To change the title, modify $emailData['title'].
    - To change the body, modify the $emailData['emailBody'].
    - To change the footer, modify $emailData[‘helpContact’].
    
The email template will look similar to this:

![Sample ESS Email](/docs/sampleEmailTemplate.png)

#### Other notes

- The application will not work without at least one semester in the database. I added a temporary one for you, which you can edit once you login.
- Although I added a temporary user for you of admin level privilege, you can go to 'yourDomain'/Libservices/index.php/auth/index to add more users. This link is found in the application as well in the add employee section with further information. You will not be able to access this page without a high privilege user logged in.

## Built With

- CodeIgniter PHP framework - https://www.codeigniter.com/
- Foundation for Sites by ZURB front-end framework - https://foundation.zurb.com

## Authors

- Anderson Uribe-Rodriguez
- [Yi Meng Chen](https://www.linkedin.com/in/yimechen?trk=chatin_wnc_redirect_pubprofile&ctx=cnpartner&trk=chatin_me_view-profile_wnc&from=singlemessage&isappinstalled=0)

## Acknowledgments

#### Plugins
- CodeIgniter Ion Auth by Ben Edmunds - https://github.com/benedmunds/CodeIgniter-Ion-Auth
- signature pad by Szymon Nowak - https://github.com/szimek/signature_pad
- jQuery.floatThead by Misha Koryak - http://mkoryak.github.io/floatThead/
- jQuery Timepicker Addon by Trent Richardson - https://trentrichardson.com/examples/timepicker/

#### People or Insitution
- Ursula C. Schwerin Library at the City College of Technology of New York for providing the servers and helping shape the application through usage and recommendations. 

## License
- This project is licensed under the MIT License - see the [LICENSE.md](/LICENSE.md) file for details
