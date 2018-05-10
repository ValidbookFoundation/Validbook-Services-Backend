**Change Password**
----
  Returns json data about status of changing user password.

* **URL**

  /v1/users/change-password

* **Method:**

  `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

  ```
    {
      current_password: "123456",
      new_password: "12345678",
      confirm_password: "12345678",
    }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
    {
      "status": "success"
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
                "message": {
                    "current_password": [
                        "Current password is not valid"
                    ],
                    "confirm_password": [
                        "Confirm Password must be equal to \"New Password\"."
                    ]
                }
            }
        ]
    }
  ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/users/change-password",
      dataType: "json",
      data: {
          current_password: "123456",
          new_password: "12345678",
          confirm_password: "12345678"
      },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```