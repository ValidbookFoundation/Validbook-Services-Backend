**Open document for signature**
----
  Returns json data about a status of open document for signature.

* **URL**

  /v1/documents/`doc_id`/open-for-sig
  
* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
    
   `doc_id=[integer]` <br/>

* **Data Params**


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
   {
       "status": "success",
       "data": "<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <title>Title</title>\r\n</head>\r\n<body>\r\n<div class=\"new\" style=\"background-color: #FF0000;\">\r\n    This is a test document\r\n</div>\r\n<script>\r\n    alert(\"Hi\");\r\n</script>\r\n</body>\r\n</html>\n4ETbppyHC58BIUwZ7QFUu3OGxzwrdGav-nonce-create-timestamp-23-Nov-2017 14:11:06\n4ETbppyHC58BIUwZ7QFUu3OGxzwrdGav-nonce-create-timestamp-23-Nov-2017 14:11:06"
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
      url: "/v1/documents/12/open-for-sig",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```