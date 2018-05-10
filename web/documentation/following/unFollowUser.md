**Unfollow user in Channel**
----
    Returns json data about status of unfollowing user (all stories from all channels except follow book stories).

* **URL**

    v1/follows/simple-user-unfollow

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required:**
    
    `user_id=[integer]`

* **Data Params**

    ```
    {
        user_id: 55
    }
    ```

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
   {
       "status": "success",
       "data": {
           "is_follow": false
       }
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
        url: "v1/follows/simple-user-unfollow",
        dataType: "json",
        type : "POST",
        data: {
            user_id: 55
        },
    
        success : function(r) {
            console.log(r);
        }
    });
    ```