**Delete channel**
----
  Returns json data about status of deleting channel.

* **URL**

  /v1/channels/`id`

* **Method:**

  `DELETE`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

   **Required:**
   
  `id=[integer]`

* **Data Params**

  ```
  
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "ok",
      "data": "Channel has been successfully deleted"
    }
  ```
 
* **Error Response:**

  * **Code:** 401 <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
        {
          "message": "You are not allowed to perform this action",
          "code": 401
        }
      ]
    }
  ```

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/channels/10",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```