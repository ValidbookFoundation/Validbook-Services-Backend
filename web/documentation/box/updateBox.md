**Update Box**
----
  Returns json data about a status of updating box.

* **URL**

  /v1/boxes/`box_id`
  
* **Method:**

  `PATCH`
  
*  **Request Headers**

   `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
   
*  **URL Params**
    
   **Required:**
   `box_id=[integer]` <br/>

* **Data Params**
    ```
      {
          "name": "Edited Interests",
          "description": "New description"  
      }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
      "status": "success",
      "data": {
        "id": 419,
        "name": "Edited Interests",
        "key": "edited-interests",
        "description": "New description",
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
      url: "/v1/boxes/419",
      dataType: "json",
      data: {name: "Edited Interests", description: "Lorem Ipsum"},
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```