**Create new document**
----
  Returns json data about status of creating new doc.

* **URL**

  /v1/documents

* **Method:**

  `POST`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

  ```
    {
     	"box_slug": "board",
     	"title": "Test Document",
     	"content": "<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <title>Title<\/title>\r\n<\/head>\r\n<body>\r\n<div class=\"new\" style=\"background-color: #FF0000;\">\r\n    This is a test document\r\n<\/div>\r\n<script>\r\n    alert(\"Hi\");\r\n<\/script>\r\n<\/body>\r\n<\/html>",
         "user_id": 1,
         "is_encrypted":  1 or 0 ,
         "hash" : "0xe01d3891dc951e1138097775e7606ec57253579693f93e9c8dc6b455e08b87ba" - if `is_encrypted` = 1
    }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
   {
     "status": "success",
     "data": {
         "id": 6,
         "title": "Test Document2",
         "type": "custom",
         "box_id": 2,
         "user_id": 1,
         "icon": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/aQfsfk/previews/doder.jpg",
         "url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document2_jimbo.fry.md",
         "created": "26 Sep 2017",
         "signatures": [],
         "settings": {
             "can_see_content": 1,
             "can_sign": 0,
             "users_array": {
                 "users_can_see_content": [],
                 "users_can_sign": []
             }
         }
     }
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

  ```
    $.ajax({
      url: "/v1/documents",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```
  
