**Create new channel**
----
  Returns json data about status of creating new channel.

* **URL**

  /v1/channels

* **Method:**

  `POST`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

  ```
   {
   	"name" : "Test",
   	"description" : "Test",
   	"content":{
   	"added":{
   		"people": [
   			3
   		],
   		"books": [222]
   	}
   	}
   }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
  {
      "status": "success",
      "data": {
          "id": 104,
          "name": "Test",
          "slug": "test",
          "people": [
              [
                  {
                      "id": "3",
                      "first_name": "Denis",
                      "last_name": "Dragomirik",
                      "slug": "denis.dragomirik",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/3/jIUoIo6DwEhHqR40PDojN1-WAEHNCIN8.jpg",
                      "count": {
                          "followed_books": 1,
                          "blocked_books": 0
                      }
                  }
              ]
          ],
          "counts": {
              "books": 1,
              "people": 1
          }
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
          "message": {
            "name": [
              "Name cannot be blank."
            ]
          },
          "code": 404
        }
      ]
    }
  ```

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/channels",
      dataType: "json",
      data: {
          name: "Test channel", 
          description: "Test description"
      },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```