**Update channel**
----
  Returns json data about status of updating channel.

* **URL**

  /v1/channels/`channel_id`

* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
     
*  **URL Params**

   **Required:**
   
  `id=[integer]`

* **Data Params**

  ```
    {"content":{
    		"added":{
    		"people": [
    		4, 3, 4
    		],
    		"books": [233]
    	}, 
    		"removed":{
    		"people": [
    		
    		]
    		}
    }
    }
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
        "status": "success",
        "data": {
            "id": 104,
            "name": "Test",
            "people": [
                [
                    {
                        "id": "3",
                        "first_name": "Denis",
                        "last_name": "Dragomirik",
                        "slug": "denis.dragomirik",
                        "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/3/jIUoIo6DwEhHqR40PDojN1-WAEHNCIN8.jpg",
                        "counts": {
                            "followed_books": 1,
                            "blocked_books": 0
                        }
                    },
                    {
                        "id": "4",
                        "first_name": "Alex",
                        "last_name": "Tykhonchuk",
                        "slug": "alex.tykhonchuk",
                        "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/I0Oajix9JlBfmYpSS6KULndIM1oCAI3p.jpg",
                        "counts": {
                            "followed_books": 1,
                            "blocked_books": 0
                        }
                    }
                ]
            ],
            "counts": {
                "books": 2,
                "people": 2
            }
        }
    }
  ```
 
* **Error Response:**

  * **Code:** 401 <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
        {
          "message": "You are not allowed to perform this action",
          "code": 401
        }
      ]
    }
  ```

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/channels/10",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```