**Create new story**
----
    Returns json data about status of creating new story.
    Allowed visibility types: [0, 1, 2]
    If visibility_type = 2, `users_custom_visibility` must be set to post params.
    Else `users_custom_visibility` is an empty array

* **URL**

    /v1/stories

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

    ```
   {
       "description": "Test story-2",
       "books": ["2-wor","3"],
       "in_storyline": 1,
       "in_channels": 1,
       "in_books": 1,
       "visibility": 2,
       "users_custom_visibility": [5, 6, 7],
       "image_sizes": [
          {
       "original" : "2000x1000",
       "thumbnail": "1000x500"
          },
          {
       "original" : "1200x600",
       "thumbnail": "800x300"
          }
       ]
   }
    ```
   * **Body Content**
      `file[]`

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
   {
       "status": "success",
       "data": [
           {
               "id": 1326,
               "text": "Test story-2",
               "date": {
                   "created": "07 Aug 2017",
                   "exactCreated": "07 Aug 2017 9:10:45",
                   "startedOn": "07 Aug 2017",
                   "completedOn": null
               },
               "loudness": {
                   "inStoryline": true,
                   "inChannels": true,
                   "inBooks": true
               },
               "visibility": {
                   "value": 2,
                   "users_custom_visibility": [
                       5,
                       6,
                       7
                   ]
               },
               "user": {
                   "id": 1,
                   "fullName": "Jimbo Fry",
                   "slug": "jimbo.fry",
                   "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
               },
               "images": [],
               "links": [],
               "books": [],
               "likes": {
                   "qty": 0,
                   "is_liked": false,
                   "people_list": []
               },
               "comments": []
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
        url: "/v1/stories",
        dataType: "json",
        type : "POST",
        data: {
            description: "Test story",
            books: ["1-interests", "2-sport"],
            in_storyline: 1,
            in_channels: 1,
            in_books: 1,
            visibility: 2,
            users_custom_visibility: [5, 6, 7]
        },
    
    success : function(r) {
        console.log(r);
    }
    });
    ```