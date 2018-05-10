**Delete message**
----
  Returns json data about status of deleting message

* **URL**

  /v1/messages/`message_id`

* **Method:**

  `DELETE`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

   **Required:**
   
  `message_id=[integer]`

* **Data Params**

  ```
  
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "success"
      "data" : [] 
    }
  ```
 
* **Error Response:**

  * **Code:** 401 Unauthorized <br />
  * **Code:** 404 NOT FOUND<br />
  * **Code:** 422 Unprocessable Entity <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
        {
          "message": message,
          "code": Code
        }
      ]
    }
  ```

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/messages/1",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```