**Move box**
----
  Returns json data about status of moving box node.

* **URL**

  /v1/boxes/`box_id`/move

* **Method:**
  `PATCH`
  
*  **Request Headers**

   `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
   
*  **URL Params**
    
   **Required**
   
    `box_id=[integer]` </br>

* **Data Params**

   **Required**
    
    `box_before_id=[integer]`<br/>
    OR <br/>
    `box_parent_id=[integer]`
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "success",
      "data": []
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
      url: "/v1/boxes/1/move",
      dataType: "json",
      type : "POST",
      data: {
          box_parent_id: 398
      },
      success : function(r) {
        console.log(r);
      }
    });
  ```