**All tab search**
----
    Returns json data about result of all tab search.
    Sorting
    1. users(friends, following, followers, other)
    2. followed user's books and other
    3. stories user's following and other

* **URL**

    v1/search/all:q

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required:**
    
    `q=[string]`

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
  {
      "status": "success",
      "data": {
          "users": [
              {
                  "id": 41,
                  "first_name": "Alex",
                  "last_name": "Alex",
                  "slug": "alex.alex",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png",
                  "relation": "following"
              },
              {
                  "id": 20,
                  "first_name": "Andreia",
                  "last_name": "Lage",
                  "slug": "andreia.lage",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/32x32.jpg",
                  "relation": "following"
              },
              {
                  "id": 4,
                  "first_name": "Alex",
                  "last_name": "Tykhonchuk",
                  "slug": "alex.tykhonchuk",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg",
                  "relation": "following"
              },
              {
                  "id": 6,
                  "first_name": "Andriy",
                  "last_name": "Borshchuk",
                  "slug": "andriy.borshchuk",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/6/32x32.jpg",
                  "relation": "following"
              },
              {
                  "id": 13,
                  "first_name": "Andrei",
                  "last_name": "Podik",
                  "slug": "andrei.podik",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/13/32x32.jpg",
                  "relation": "following"
              },
              {
                  "id": 7,
                  "first_name": "Galya",
                  "last_name": "An",
                  "slug": "galya.an",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/7/32x32.jpg",
                  "relation": "following"
              }
          ],
          "books": [
              {
                  "id": 246,
                  "name": "API",
                  "slug": "246-api",
                  "description": "",
                   "cover": {
                        "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                         "color": null
                         },
                  "created": "20 Jun 2017",
                  "owner": {
                      "id": 4,
                      "fullName": "Alex Tykhonchuk",
                      "slug": "alex.tykhonchuk",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                  },
                  "counters": {
                      "stories": "5",
                      "followers": "0"
                  }
              },
              {
                  "id": 267,
                  "name": "Loreal Facebook App",
                  "slug": "267-loreal-facebook-app",
                  "description": "",
                  "cover": "",
                  "created": "20 Jun 2017",
                  "owner": {
                      "id": 4,
                      "fullName": "Alex Tykhonchuk",
                      "slug": "alex.tykhonchuk",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                  },
                  "counters": {
                      "stories": "1",
                      "followers": "0"
                  }
              },
              {
                  "id": 273,
                  "name": "Content and blog sites",
                  "slug": "273-content-and-blog-sites",
                  "description": "",
                    "cover": {
                         "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                          "color": null
                          },
                  "created": "20 Jun 2017",
                  "owner": {
                      "id": 4,
                      "fullName": "Alex Tykhonchuk",
                      "slug": "alex.tykhonchuk",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                  },
                  "counters": {
                      "stories": "0",
                      "followers": "0"
                  }
              },
              {
                  "id": 145,
                  "name": "Karma",
                  "slug": "145-karma",
                  "description": "This is my Karma book. It is an experiment to see if such books can be useful. Let's see! If you worked or interacted with me somehow, feel free to leave in this book a feedback-story about this. I promise to not delete stories, unless they are spam.",
                  "cover": "",
                  "created": "20 Jun 2017",
                  "owner": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "counters": {
                      "stories": "2",
                      "followers": "0"
                  }
              }
          ],
          "stories": [
              {
                  "id": 1295,
                  "text": "<p>azxs</p>\n",
                  "date": {
                      "created": "17 Jul 2017",
                      "exactCreated": "17 Jul 2017 14:50:46",
                      "startedOn": "17 Jul 2017",
                      "completedOn": null
                  },
                  "loudness": {
                      "inStoryline": true,
                      "inChannels": true,
                      "inBooks": true
                  },
                  "visibility": {
                      "status": "public",
                      "customUsers": []
                  },
                  "user": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "images": [],
                  "links": [],
                  "books": [
                      {
                          "id": 145,
                          "name": "Karma",
                          "slug": null
                      }
                  ],
                  "likes": {
                      "qty": 0,
                      "is_liked": false,
                      "people_list": []
                  },
                  "comments": []
              },
              {
                  "id": 1294,
                  "text": "<p>aqws</p>\n",
                  "date": {
                      "created": "17 Jul 2017",
                      "exactCreated": "17 Jul 2017 14:50:25",
                      "startedOn": "17 Jul 2017",
                      "completedOn": null
                  },
                  "loudness": {
                      "inStoryline": true,
                      "inChannels": true,
                      "inBooks": true
                  },
                  "visibility": {
                      "status": "public",
                      "customUsers": []
                  },
                  "user": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "images": [],
                  "links": [],
                  "books": [
                      {
                          "id": 65,
                          "name": "Wallbook",
                          "slug": null
                      }
                  ],
                  "likes": {
                      "qty": 0,
                      "is_liked": false,
                      "people_list": []
                  },
                  "comments": []
              },
              {
                  "id": 1272,
                  "text": "<p>abracabra</p>\n",
                  "date": {
                      "created": "07 Jul 2017",
                      "exactCreated": "07 Jul 2017 7:54:50",
                      "startedOn": "07 Jul 2017",
                      "completedOn": null
                  },
                  "loudness": {
                      "inStoryline": true,
                      "inChannels": true,
                      "inBooks": true
                  },
                  "visibility": {
                      "status": "public",
                      "customUsers": []
                  },
                  "user": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "images": [],
                  "links": [],
                  "books": [
                      {
                          "id": 65,
                          "name": "Wallbook",
                          "slug": null
                      },
                      {
                          "id": 66,
                          "name": "Interests",
                          "slug": null
                      },
                      {
                          "id": 145,
                          "name": "Karma",
                          "slug": null
                      },
                      {
                          "id": 146,
                          "name": "Ask me anything",
                          "slug": null
                      },
                      {
                          "id": 147,
                          "name": "Closed Book",
                          "slug": null
                      },
                      {
                          "id": 148,
                          "name": "Life",
                          "slug": null
                      }
                  ],
                  "likes": {
                      "qty": 2,
                      "is_liked": false,
                      "people_list": [
                          {
                              "user": {
                                  "id": 1,
                                  "fullName": "Jimbo Fry",
                                  "slug": "jimbo.fry",
                                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                  "is_friend": false,
                                  "is_owner": false
                              }
                          },
                          {
                              "user": {
                                  "id": 50,
                                  "fullName": "qwerty qwertyu",
                                  "slug": "qwerty.qwertyu",
                                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png",
                                  "is_friend": false,
                                  "is_owner": false
                              }
                          }
                      ]
                  },
                  "comments": [
                      {
                          "id": 10,
                          "entity": "story",
                          "entity_id": 1272,
                          "date": "10 Jul 2017",
                          "parent_id": 0,
                          "parent": [],
                          "content": "1",
                          "children": null,
                          "user": {
                              "id": 1,
                              "first_name": "Jimbo",
                              "last_name": "Fry",
                              "slug": "jimbo.fry",
                              "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                              "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                               "cover": {
                                    "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                                     "color": null
                                     },
                              "isFollowing": false
                          }
                      },
                      {
                          "id": 11,
                          "entity": "story",
                          "entity_id": 1272,
                          "date": "10 Jul 2017",
                          "parent_id": 0,
                          "parent": [],
                          "content": "2",
                          "children": null,
                          "user": {
                              "id": 1,
                              "first_name": "Jimbo",
                              "last_name": "Fry",
                              "slug": "jimbo.fry",
                              "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                              "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                "cover": {
                                     "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                                      "color": null
                                      },
                              "isFollowing": false,
                          }
                      },
                      {
                          "id": 12,
                          "entity": "story",
                          "entity_id": 1272,
                          "date": "10 Jul 2017",
                          "parent_id": 0,
                          "parent": [],
                          "content": "3",
                          "children": null,
                          "user": {
                              "id": 1,
                              "first_name": "Jimbo",
                              "last_name": "Fry",
                              "slug": "jimbo.fry",
                              "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                              "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                              "cover": {
                                   "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                                    "color": null
                                    },
                              "isFollowing": false
                          }
                      },
                      {
                          "id": 13,
                          "entity": "story",
                          "entity_id": 1272,
                          "date": "10 Jul 2017",
                          "parent_id": 0,
                          "parent": [],
                          "content": "4",
                          "children": null,
                          "user": {
                              "id": 1,
                              "first_name": "Jimbo",
                              "last_name": "Fry",
                              "slug": "jimbo.fry",
                              "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                              "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                "cover": {
                                     "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                                      "color": null
                                      },
                              "isFollowing": false,
                          }
                      }
                  ]
              },
              {
                  "id": 1269,
                  "text": "<p>ascdercs</p>\n",
                  "date": {
                      "created": "06 Jul 2017",
                      "exactCreated": "06 Jul 2017 6:38:27",
                      "startedOn": "06 Jul 2017",
                      "completedOn": null
                  },
                  "loudness": {
                      "inStoryline": false,
                      "inChannels": false,
                      "inBooks": false
                  },
                  "visibility": {
                      "status": "public",
                      "customUsers": []
                  },
                  "user": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "images": [],
                  "links": [],
                  "books": [
                      {
                          "id": 65,
                          "name": "Wallbook",
                          "slug": null
                      }
                  ],
                  "likes": {
                      "qty": 0,
                      "is_liked": false,
                      "people_list": []
                  },
                  "comments": []
              },
              {
                  "id": 1261,
                  "text": "<p>&lt;script&gt;alert('gfgfgf')&lt;/script&gt;</p>\n",
                  "date": {
                      "created": "04 Jul 2017",
                      "exactCreated": "04 Jul 2017 7:50:31",
                      "startedOn": "04 Jul 2017",
                      "completedOn": null
                  },
                  "loudness": {
                      "inStoryline": true,
                      "inChannels": true,
                      "inBooks": true
                  },
                  "visibility": {
                      "status": "public",
                      "customUsers": []
                  },
                  "user": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "images": [],
                  "links": [],
                  "books": [
                      {
                          "id": 65,
                          "name": "Wallbook",
                          "slug": null
                      }
                  ],
                  "likes": {
                      "qty": 0,
                      "is_liked": false,
                      "people_list": []
                  },
                  "comments": []
              },
              {
                  "id": 1249,
                  "text": "<p></p>\n<img src=\"http://i.imgur.com/oQnm2Az.jpg\" style=\"float:none;height: auto;width: auto\"/>\n<p></p>\n<img src=\"http://i.imgur.com/PrVYLMc.png\" style=\"float:none;height: auto;width: auto\"/>\n<p></p>\n<img src=\"http://i.imgur.com/3BoeY43.png\" style=\"float:none;height: auto;width: auto\"/>\n<p></p>\n",
                  "date": {
                      "created": "23 Jun 2017",
                      "exactCreated": "23 Jun 2017 13:57:35",
                      "startedOn": "23 Jun 2017",
                      "completedOn": null
                  },
                  "loudness": {
                      "inStoryline": true,
                      "inChannels": true,
                      "inBooks": true
                  },
                  "visibility": {
                      "status": "public",
                      "customUsers": []
                  },
                  "user": {
                      "id": 1,
                      "fullName": "Jimbo Fry",
                      "slug": "jimbo.fry",
                      "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                  },
                  "images": [],
                  "links": [],
                  "books": [
                      {
                          "id": 65,
                          "name": "Wallbook",
                          "slug": null
                      }
                  ],
                  "likes": {
                      "qty": 1,
                      "is_liked": false,
                      "people_list": [
                          {
                              "user": {
                                  "id": 1,
                                  "fullName": "Jimbo Fry",
                                  "slug": "jimbo.fry",
                                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                  "is_friend": false,
                                  "is_owner": false
                              }
                          }
                      ]
                  },
                  "comments": []
              }
          ]
      }
  }
    ```

* **Error Response:**

 * **Code:** 400 Bad Request <br />
 * **Code:** 404 NOT FOUND <br />
 * **Code:** 401 Unauthorized <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
           {
             "code": Code,
             "message": {message}
           }
      ]
    }
  ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/search/all?q=a",
        dataType: "json",
        type : "GET",
        data: {q: "a"},
        success : function(r) {
            console.log(r);
        }
    });
    ```