**Get Suggested Users**
----
    Returns json data about suggested users for People Tab.

* **URL**

    v1/people/suggested

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
    `page=[integer]` - page = 1 by default

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
    {
      "status": "success",
      "data": [
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
        url: "/v1/people/suggested",
        dataType: "json",
        type : "GET",
        data: {page: 2},
        success : function(r) {
            console.log(r);
        }
    });
    ```