**Ignore knock book**
----
  Returns json data about status of ignore knocking book.

* **URL**

  /v1/knock-books/`knock_id`/ignore

* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
      
*  **URL Params**
  `knock_id=[integer]`

* **Data Params**

  ```
  ```
    
* **Success Response:**

  * **Code:** 200 Ok <br />
    **Content:** 
  ```
  {
      "status": "success",
      "data":  {}
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
      url: "/v1/knock-books/1/ignore",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```