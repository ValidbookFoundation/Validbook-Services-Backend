**Create new box**
----
  Returns json data about status of creating new box.

* **URL**

  /v1/boxes

* **Method:**

  `POST`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
  
*  **URL Params**


* **Data Params**

  ```
    {
        "id" : 1,
       	"name": "Test",
       	"description" : "test",
       	"parent_id": 1,
        "can_see_exists" : 1,
        "can_see_content" : 2,
        "can_add_documents" : 0,
        "can_delete_documents" : 0,
        "users_can_see_exists" : [],
        "users_can_see_content" : [4,77,109],
        "users_can_add_documents" : [],
        "users_can_delete_documents" : []
    }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": {
           "id": 138,
           "name": "Test",
           "key": "test",
           "description": "test"
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
      url: "/v1/boxes",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```