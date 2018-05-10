**View Box**
----
  Returns json data about a user boxes.

* **URL**

  /v1/boxes/values-for-options
  
* **Method:**

  `GET`

*  **Request Headers**

   `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
   
    
*  **URL Params**
    

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": [
            {
                "name": "can_see_exists",
                "values": {
                    "0": "only you",
                    "1": "anyone",
                    "2": "specific people"
                },
                "customFieldName": "users_can_see_exists"
            },
            {
                "name": "can_see_content",
                "values": {
                    "0": "only you",
                    "1": "anyone",
                    "2": "specific people"
                },
                "customFieldName": "users_can_see_content"
            },
            {
                "name": "can_add_documents",
                "values": {
                    "0": "only you",
                    "1": "anyone",
                    "2": "specific people"
                },
                "customFieldName": "users_can_add_documents"
            },
            {
                "name": "can_delete_documents",
                "values": {
                    "0": "only you",
                    "1": "anyone",
                    "2": "specific people"
                },
                "customFieldName": "users_can_delete_documents"
            }
        ]
    }
    ```
 
* **Error Response:**

  * **Code:** 404 <br />
    **Content:** 
    ```
    {
        "status": "error",
        "errors": [
            {
                "code": 401,
                "message": "Your request was made with invalid credentials."
            }
        ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/boxes/values-for-options",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```