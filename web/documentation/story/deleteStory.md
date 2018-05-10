**Delete story**
----
  Returns json data about status of deleting story.

* **URL**

  /v1/stories/`id`

* **Method:**

  `DELETE`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
  
*  **URL Params**

   **Required:**
   
  `id=[integer]`

* **Data Params**

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
        "status": "success"
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
      url: "/v1/stories/10",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```