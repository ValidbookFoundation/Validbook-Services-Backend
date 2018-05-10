**Update Document**
----
  Returns json data about a status of updating doc.

* **URL**

  /v1/documents/`doc_id`
  
* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
   `doc_id=[integer]` <br/>

* **Data Params**
    ```
     {
    	"title": "Update document",
    	"content": "<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <title>Title<\/title>\r\n<\/head>\r\n<body>\r\n<div class=\"new\" style=\"background-color: #FF0000;\">\r\n    This is a  update test document\r\n<\/div>\r\n<script>\r\n    alert(\"Hi\");\r\n<\/script>\r\n<\/body>\r\n<\/html>", 
    	"hash" : "453gdsfgdfgdfget2342345234523dfg"  - `requeried if document was encrypted`
     }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
      "status": "success",
      "data": {
          "id": 12,
          "title": "Edited Interests",
          "type": "custom",
          "box_id": 2,
          "user_id": 1,
          "icon": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/aQfsfk/previews/doder.jpg",
          "url": "https://s3-us-west-2.amazonaws.com/dev.validbook/documents/2017/09/05/1/1zRv52.html"
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

  ```javascript
    $.ajax({
      url: "/v1/documents/12",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```