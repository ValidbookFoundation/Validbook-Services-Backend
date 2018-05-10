**Show Channel**
----
  Returns json data about a single channel.

* **URL**

  /v1/channels/`channel_slug`

* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
  
*  **URL Params**

   `page=[integer]` - page = 1 by default

   **Required:**
 
   `access_token=[string]`

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    {
      "status": "ok",
      "data": {
        "id": 7,
        "name": "Test Channel",
        "slug": "test-channel-2",
        "description": "Test channel description",
        "stories" : [
            {
              "id": "1",
              "text": "<p>Test Story 1</p>",
              "date": {
                "created": "24 May 2017",
                "exactCreated": "24 May 2017 11:59:00",
                "startedOn": "24 May 2017",
                "completedOn": null
              },
              "user": {
                "id": 1,
                "fullName": "John Smith",
                "slug": "john-smith",
                "avatar": ""
              },
              "images": [
                "https://s3-us-west-2.amazonaws.com/dev.validbook/stories/2017/05/19/661/6LLYdffd5bjhwPGscglWiHYm6m-3i8b3.jpg"
              ],
              "links": [],
              "likes": {
                "qty": 0,
                "is_liked": false,
                "people_list": []
              }
            },
            {
              "id": "2",
              "text": "<p>Test story 2</p>",
              "date": {
                  "created": "24 May 2017",
                  "exactCreated": "24 May 2017 11:59:00",
                  "startedOn": "24 May 2017",
                  "completedOn": null
              },
              "user": {
                "id": 1,
                "fullName": "John Smith",
                "slug": "john-smith",
                "avatar": ""
              },
              "images": [],
              "links": [
                {
                  "id": 186,
                  "link": "http://www.programmerinterview.com/index.php/database-sql/advanced-sql-interview-questions-and-answers/",
                  "title": "",
                  "description": "Advanced SQL Interview Questions and Answers Here are some complex SQL interview problems that are for people who are looking for more advanced and challenging questions, along with the answers and complete explanations. Try to figure out the answer to the questions yourself before reading the answers. Suppose we have 2 tables called Orders and …",
                  "twitter_author": "",
                  "twitter_avatar": ""
                }
              ],
              "likes": {
                "qty": 2,
                "is_liked": true,
                "people_list": [
                    {
                      "user": {
                        "id": 1,
                        "fullName": "John Matte",
                        "slug": "john.matte",
                        "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/100x100.png",
                        "is_friend": false,
                        "is_owner": true
                      }
                    },
                    {
                      "user": {
                        "id": 2,
                        "fullName": "Matias Eclof",
                        "slug": "matias.eclof",
                        "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/100x100.png",
                        "is_friend": true,
                        "is_owner": false
                      }
                    }
                  ]
              }
            }
        ]
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
          "message": "Channel doesn't exist",
          "code": 404
        }
      ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/channels/sport?access_token=Fkc5AVudvdGj1dHUEy6w3tTwVqYjkues",
      dataType: "json",
      type : "GET",
      data: {"page": 2},
      success : function(r) {
        console.log(r);
      }
    });
  ```