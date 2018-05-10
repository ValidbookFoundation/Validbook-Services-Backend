**User Logout**
----
  Returns json data status of user logout.

* **URL**

  /v1/users/logout

* **Method:**

  `POST`
  
*  **URL Params**

  None  

* **Data Params**

  None
    
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

  * **Code:** 404 NOT FOUND <br />
  * **Code:** 422 [] <br />

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/users/logout",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```