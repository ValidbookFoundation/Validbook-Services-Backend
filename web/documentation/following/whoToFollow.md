**Who to follow**
----
    Returns json data for Who To Follow block.

* **URL**

    v1/follows/who-to-follow

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
    {
        "status": "success",
        "data": [
            {
                "id": "4",
                "first_name": "John",
                "last_name": "Smith",
                "full_name": "John Smith",
                "slug": "john smith",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/05/29/4/100x100.jpg",
                "is_follow": false,
                  "cover": {
                       "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                        "color": null
                        },
            },
            {
                "id": "4",
                "first_name": "John2",
                "last_name": "Smith",
                "full_name": "John2 Smith",
                "slug": "john2 smith",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/05/29/4/100x100.jpg",
                "isFollowing": false,
                  "cover": {
                       "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                        "color": null
                        },
            },
            {
                "id": "4",
                "first_name": "John3",
                "last_name": "Smith",
                "full_name": "John3 Smith",
                "slug": "john3 smith",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/05/29/4/100x100.jpg",
                "is_follow": false,
                   "cover": {
                        "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                         "color": null
                         },
            }
        ]
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
        url: "/v1/follows/who-to-follow",
        dataType: "json",
        type : "GET",
        success : function(r) {
            console.log(r);
        }
    });
    ```