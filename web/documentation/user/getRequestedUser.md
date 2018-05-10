**Requested User**
----
  Returns json data about a requested user.

* **URL**

  /v1/users/`user_slug`/requested-user:auth_user_slug

* **Method:**

  `GET`

*  **Request Headers**

    
*  **URL Params**

  `user_slug=[string]`  
  `auth_user_slug=[string]` (optional) if authorized user

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
      "status": "success",
      "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Smith",
        "slug": "john.smith",
        "avatar230": "http://placehold.it/230x230",
        "avatar32": "http://placehold.it/32x32",
        "isFollowing": true
        "cover": {
                  "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/user-covers/1900x235_aOg_BDbQmApCv-iXVd4HKvnJmbXDSG0B.jpg",
                  "picture_small": null,
                  "color": null
              },
        "count_follows_book": 0
      }
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
          "message": "User doesn't exist",
          "code": 404
        }
      ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/users/john.smith/requested-user?auth_user_slug=jimbo.fry",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```