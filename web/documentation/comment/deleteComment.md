**Delete comment**
----
  Returns json data about status of deleting comment.

* **URL**

  /v1/comments/`id`


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

  * **Code:** 401 Unauthorized <br />
  * **Code:** 404 NOT FOUND<br />
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
      url: "/v1/comments/10",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```