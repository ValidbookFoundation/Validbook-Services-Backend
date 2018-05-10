**Get Validbook services**
----
  Returns json data: list of Valibook services and their urls

* **URL**

  /v1/well-known/services-info
  
* **Method:**

  `GET`
  
*  **Request Headers**

    
*  **URL Params**
    

* **Data Params**


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": {
             "wiki": "http://wiki-futurama1x.validbook.org",
             "forum": "http://forum-futurama1x.validbook.org",
             "drive": "http://drive-futurama1x.validbook.org"
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
      url: "/v1/well-known/services-info",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```