**Get Followers Users**
----
    Returns json data about followers users for People Tab.

* **URL**

    v1/people/followers

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    `user_slug=[string]`<br/>
    `page=[integer]` - page = 1 by default

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
    {
      "status": "success",
      "data": [
        "count": 2,
        "users": [
          {
            "id": "1",
            "first_name": "John",
            "last_name": "Caspar",
            "slug": "john.caspar",
            "avatar": "",
            "isFollowing": true
          },
          {
            "id": "2",
            "first_name": "Vlad",
            "last_name": "Nelson",
            "slug": "vlad.nelson",
            "avatar": "",
            "isFollowing": false
          }
        ]
      ]
    }
    ```

* **Error Response:**

* **Code:** 400 <br />
**Content:**
    ```
    {
      "status": "error",
      "errors": [
        {
          "code": 400,
          "message": "Your request was made with invalid credentials."
        }
      ]
    }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/people/followers",
        dataType: "json",
        type : "GET",
        data: {user_slug: "jogn-smith", page: 2},
        success : function(r) {
            console.log(r);
        }
    });
    ```