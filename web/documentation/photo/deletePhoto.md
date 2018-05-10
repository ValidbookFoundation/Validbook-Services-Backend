**Delete photo**
----
  Returns json data about status of deleting photo.

* **URL**

  /v1/photos/`photo_id`

* **Method:**

  `DELETE`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

   `photo_id=[integer]` <br/>
  
* **Data Params**

     `entity=[string]` <br/>
     `entity_id=[integer]` <br/> 
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "success",
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
      url: "/v1/photos/1",
      dataType: "json",
      type : "DELETE",
      success : function(r) {
        console.log(r);
      }
    });
  ```