**Get User's All People**
----
    Returns json data about all user's people.

* **URL**

    v1/people/all:user_slug

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
            {
                "id": "60",
                "first_name": "John",
                "last_name": "Smith",
                "full_name": "John Smith",
                "slug": "john.smith",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png",
                "isFollowing": true,
                "cover": {
                        "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                         "color": null
                },
            },
            {
                "id": "61",
                "first_name": "John 2",
                "last_name": "Smith",
                "full_name": "John Smith",
                "slug": "john2.smith",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png",
                "isFollowing": true,
                "cover": {
                        "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                         "color": null
                },
            },
        ]
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
          "code": 404,
          "message": "Your request was made with invalid credentials."
        }
      ]
    }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/people/all?user_slug=olga.sochneva",
        dataType: "json",
        type : "GET",
        success : function(r) {
            console.log(r);
        }
    });
    ```