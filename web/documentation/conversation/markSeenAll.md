**Mark All New Conversations As Seen **
----
    Returns json data about status of marking all conversations as seen. 

* **URL**

    v1/conversations/seen-all

* **Method:**

    `POST`
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Success Response:**

* **Code:** 201 <br />
**Content:**
    ```
    {
        "status": "success"
    }
    ```

* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    
      **Content:** 
    ```
      {
          "status": "error",
          "errors": [
              {
                  "code": 404,
                  "message": ""
              }
          ]
      }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/conversations/seen-all",
        dataType: "json",
        type : "POST",
    
        success : function(r) {
            console.log(r);
        }
    });
    ```