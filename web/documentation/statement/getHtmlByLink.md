**Get html code (template) by link**
----
  Returns json data: html code from link

* **URL**

  /v1/statements/html-template
  
* **Method:**

  `POST`
  
*  **Request Headers**
    
    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    

* **Data Params**
    ```
    {
   	"link" : "http://api-futurama1x.validbook.org/templates/1/template.html"
    }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n  <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">\n  <title>Certificate</title>\n  <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/icon?family=Material+Icons|Roboto:400,500,700\"/>\n  <style>\n    body {\n      margin: 0;\n      padding: 0;\n      font-family: Roboto;\n    }\n\n    .certificate_img {\n      background: url(http://api-futurama1x.validbook.org/templates/1/template.jpeg) no-repeat;\n      width: 960px;\n      height: 720px;\n      position: relative;\n    }\n\n    form {\n      position: relative;\n      display: flex;\n      flex-direction: column;\n      align-items: center\n    }\n\n    input,\n    textarea {\n      background-color: transparent;\n      border: none;\n      padding: 0;\n      text-align: center;\n    }\n\n    #certificate_about {\n      position: absolute;\n      top: 164px;\n      font-size: 36px;\n      width: 695px;\n    }\n\n    #full_name {\n      position: absolute;\n      top: 318px;\n      font-size: 56px;\n      width: 695px;\n    }\n\n    #description {\n      position: absolute;\n      top: 404px;\n      font-size: 18px;\n      text-transform: uppercase;\n      width: 695px;\n      resize: none;\n    }\n\n    #date {\n      font-size: 16px;\n      width: 312px;\n    }\n\n    #signature {\n      position: relative;\n      font-size: 16px;\n      width: 312px;\n      overflow: hidden;\n      text-overflow: ellipsis;\n      white-space: nowrap;\n    }\n\n    .label_text {\n      position: relative;\n      font-size: 16px;\n      text-transform: uppercase;\n      text-align: center;\n      top: 10px;\n    }\n\n    .signature_field {\n      position: relative;\n      top: 504px;\n      left: 171px;\n      width: 312px;\n      height: 19px;\n    }\n\n    .date_field {\n      position: relative;\n      top: 523px; \n      left: -171px;\n      width: 312px;\n      height: 19px;\n    }\n\n    .acnowledges {\n      position: absolute;\n      top: 278px;\n      text-transform: uppercase;\n      font-size: 18px;\n    }\n  </style>\n</head>\n<body>\n  <div class=\"certificate_img\">\n    <form>\n      <input id=\"certificate_about\" name=\"certificate_about\" type=\"text\" value=\"Certificate of About\" />\n      <div class=\"acnowledges\">this acknowledges that</div>\n      <input  id=\"full_name\" name=\"full_name\" type=\"text\" value=\"Alex Alexeev\" />\n      <textarea id=\"description\" name=\"description\" type=\"text\" rows=\"5\">has successfully completed the Excellence in Business Program</textarea>\n      <div class=\"date_field\">\n        <input id=\"date\" name=\"date\" type=\"text\" value=\"01.01.2018\" />\n        <div class=\"label_text\">Date</div>\n      </div>\n      <div class=\"signature_field\">\n        <input id=\"signature\" name=\"signature\" type=\"text\" value=\"0xcbcc2384ea3dc35b5c086d99b0dbe7489ae1d99f65d4d1d96f340c2e045ea26f33fed1d0e20efda133bee4ae877c20b0e888080404c2bfe2648d39faecadb6181b\" />\n        <div class=\"label_text\">Signature</div>\n      </div>\n    </form>\n  </div>\n</body>\n</html>"
     }
    ```
 
* **Error Response:**

   * **Code:** 400 Bad Request <br />
   * **Code:** 401 Unauthorized <br />
   * **Code:** 404 NOT FOUND<br />
   * **Code:** 422 Unprocessable Entity <br />
   * **Code:** 500 Internal Server Error<br />
     **Content:** 
     ```
        {
          "status": "error",
          "errors": [
                  {
                      "code": Code,
                      "message": string or []
                  }
              ]
        }
     ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/statements/html-template",
      dataType: "json",
      data: {
        link: "http://api-futurama1x.validbook.org/templates/1/template.html"
    },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```