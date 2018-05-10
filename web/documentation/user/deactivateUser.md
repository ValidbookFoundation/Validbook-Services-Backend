**Deactivate user account**
----
  Returns json data about status of deactivated account.

* **URL**

  /v1/users/deactivate

* **Method:**

  `POST`
 
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
     
*  **URL Params**

* **Data Params**

  ```
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
    {
        "status": "success",
        "data": []
    }
  ```
 
* **Error Response:**

   * **Code:** 401  <br />
   * **Code:** 404  <br />
    **Content:** 
  ```
  ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/users/deactivate",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```