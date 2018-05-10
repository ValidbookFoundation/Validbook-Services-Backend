**Like or Dislike Story**
----
    Returns json data about status of liking or disliking story. 
    If like is already exists form current user story will be disliked.

* **URL**

    v1/like/story

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

    ```
    {
        story_id: 55,
    }
    ```

* **Success Response:**

* **Code:** 201 <br />
**Content:**
    ```
    {
        "status": "success",
        "data": {
            "likes": {
                "qty": 2,
                "is_liked": true,
                "people_list": [
                    {
                        "user": {
                            "id": 4,
                            "fullName": "Bohdan Andriyiv",
                            "slug": "bohdan.andriyiv",
                            "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/7oSnPdcNeVyCaF0Vo2-LA0Sl_7f0aB-C.jpg",
                            "is_friend": false,
                            "is_owner": true
                        }
                    },
                    {
                        "user": {
                            "id": 69,
                            "fullName": "123456 123456",
                            "slug": "123456.123456.5",
                            "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png",
                            "is_friend": false,
                            "is_owner": false
                        }
                    }
                ]
            }
        }
    }
    ```

* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    
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
        url: "/v1/like/story",
        dataType: "json",
        type : "POST",
        data: {
            story_id: 55
        },
    
        success : function(r) {
            console.log(r);
        }
    });
    ```