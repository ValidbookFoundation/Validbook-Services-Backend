**Get Story**
----
  Returns json data about a single story.

* **URL**

  /v1/stories/`id`

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    `comments_page=[integer]`
    
   **Required:**
 
   `id=[integer]`
  

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    {
        "status": "success",
        "data": [
            {
                "id": 1,
                "text": "Wrote texts for Samerse landing page. See – <a href=\"http://samerse.com/\" rel=\"external nofollow\" target=\"_blank\">http://samerse.com/</a>  #Samerse #CopyWriting",
                "date": {
                    "created": "19 May 2015",
                    "exactCreated": "19 May 2015 19:55:00",
                    "startedOn": "19 May 2015",
                    "completedOn": null
                },
                "loudness": {
                    "inStoyline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "status": "public",
                    "customUsers": []
                },
                "user": {
                    "id": 1,
                    "fullName": "Bohdan Andriyiv",
                    "slug": "bohdan.andriyiv",
                    "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/skbKx2Jx-2678QKSvokcPa_7AFxRxaDq.jpg"
                },
                "images": [],
                "links": [],
                "books": [
                    {
                        "id": 3,
                        "name": "Validbook project",
                        "slug": "3-validbook-project"
                    }
                ],
                "likes": {
                    "qty": 0,
                    "is_liked": false,
                    "people_list": []
                },
                "comments": [
                    {
                        "id": 3,
                        "entity": "story",
                        "entity_id": 1,
                        "date": "03 Jul 2017",
                        "content": "content",
                        "parent_id": 0,
                        "parent": [],
                        "user": {
                            "id": 1,
                            "first_name": "Bohdan",
                            "last_name": "Andriyiv",
                            "slug": "bohdan.andriyiv",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/d_s7ZynB0oyNM4O_Spypwcw2Hl-Le4k1.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/skbKx2Jx-2678QKSvokcPa_7AFxRxaDq.jpg",
                            "cover": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/26/1/NzhhnkwZiwDzP-bdarcbXzrgGucl7Vjj.jpg",
                            "isFollowing": false
                        }
                    },
                    {
                        "id": 4,
                        "entity": "story",
                        "entity_id": 1,
                        "date": "03 Jul 2017",
                        "content": "content",
                        "parent_id": 0,
                        "parent": [],
                        "user": {
                            "id": 1,
                            "first_name": "Bohdan",
                            "last_name": "Andriyiv",
                            "slug": "bohdan.andriyiv",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/d_s7ZynB0oyNM4O_Spypwcw2Hl-Le4k1.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/skbKx2Jx-2678QKSvokcPa_7AFxRxaDq.jpg",
                            "cover": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/26/1/NzhhnkwZiwDzP-bdarcbXzrgGucl7Vjj.jpg",
                            "isFollowing": false
                        }
                    },
                    {
                        "id": 5,
                        "entity": "story",
                        "entity_id": 1,
                        "date": "03 Jul 2017",
                        "content": "content",
                        "parent_id": 0,
                        "parent": [],
                        "user": {
                            "id": 1,
                            "first_name": "Bohdan",
                            "last_name": "Andriyiv",
                            "slug": "bohdan.andriyiv",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/d_s7ZynB0oyNM4O_Spypwcw2Hl-Le4k1.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/skbKx2Jx-2678QKSvokcPa_7AFxRxaDq.jpg",
                            "cover": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/26/1/NzhhnkwZiwDzP-bdarcbXzrgGucl7Vjj.jpg",
                            "isFollowing": false
                        }
                    },
                    {
                        "id": 6,
                        "entity": "story",
                        "entity_id": 1,
                        "date": "03 Jul 2017",
                        "content": "content",
                        "parent_id": 0,
                        "parent": [],
                        "user": {
                            "id": 1,
                            "first_name": "Bohdan",
                            "last_name": "Andriyiv",
                            "slug": "bohdan.andriyiv",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/d_s7ZynB0oyNM4O_Spypwcw2Hl-Le4k1.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/skbKx2Jx-2678QKSvokcPa_7AFxRxaDq.jpg",
                            "cover": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/26/1/NzhhnkwZiwDzP-bdarcbXzrgGucl7Vjj.jpg",
                            "isFollowing": false
                        }
                    }
                ]
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

  ```javascript
    $.ajax({
      url: "/v1/stories/1",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```