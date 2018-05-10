**Get Book Tree**
----
  Returns json data about a user books collection tree. Types of book icon: public, private, custom, bin

* **URL**

  /v1/books/tree
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==
  
*  **URL Params**
    
    **Required:**
    
   `user_slug=[string]` <br/>

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
          "name": "root",
          "key": "root",
          "show": true,
          "children": [
            {
              "name": "Wallbook",
              "key": "wallbook",
              "icon": "public",
              "no_drag": true,
              "children": []
            },
            {
              "name": "Test",
              "key": "test",
              "icon": "private",
              "no_drag": false,
              "children": []
            }
          ]
        }   
      ]
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
      url: "/v1/books/tree",
      dataType: "json",
      data: {user_slug: "john-smith"},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```