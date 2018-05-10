**Download Document**
----
  Returns json data about  download  document.

* **URL**

  /v1/documents/`doc_id`/download
  
* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   `doc_id=[integer]`<br/>

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
     "status": "success",
     "data": {
         "document_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/59/my%2520test%2520encrypted%2520document.md"
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

* **Sample Call 1:**

  ```javascript
    $.ajax({
      url: "/v1/documents/12/download",
      dataType: "json",
      data: {},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```