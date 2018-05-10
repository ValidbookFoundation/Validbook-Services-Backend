**Get User Stories**
----
  Returns json data about a user stories.

* **URL**

  /v1/users/`user_slug`/stories

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

   `page=[integer]` - page = 1 by default

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
                "id": 1332,
                "text": "Test story-2",
                "date": {
                    "created": "08 Aug 2017",
                    "exactCreated": "08 Aug 2017 7:45:39",
                    "startedOn": "08 Aug 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
            },
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
            },
            {
                "id": 1325,
                "text": "Test story-2",
                "date": {
                    "created": "07 Aug 2017",
                    "exactCreated": "07 Aug 2017 9:09:55",
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
            },
            {
                "id": 1324,
                "text": "Test story-2",
                "date": {
                    "created": "07 Aug 2017",
                    "exactCreated": "07 Aug 2017 9:09:46",
                    "startedOn": "07 Aug 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
            },
            {
                "id": 1323,
                "text": "Test story",
                "date": {
                    "created": "07 Aug 2017",
                    "exactCreated": "07 Aug 2017 9:01:56",
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
            },
            {
                "id": 1305,
                "text": "<p>gfgfg</p>\n",
                "date": {
                    "created": "21 Jul 2017",
                    "exactCreated": "21 Jul 2017 12:35:29",
                    "startedOn": "21 Jul 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
                    "qty": 1,
                    "is_liked": true,
                    "people_list": [
                        {
                            "user": {
                                "id": 1,
                                "fullName": "Jimbo Fry",
                                "slug": "jimbo.fry",
                                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                "is_friend": false,
                                "is_owner": true
                            }
                        }
                    ]
                },
                "comments": [
                    {
                        "id": 311,
                        "entity": "story",
                        "entity_id": 1305,
                        "date": "03 Aug 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "content",
                        "children": null,
                        "user": {
                            "id": 5,
                            "first_name": "Dima",
                            "last_name": "Platonov",
                            "slug": "dima.platonov",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/230x230.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/32x32.jpg"
                        }
                    },
                    {
                        "id": 312,
                        "entity": "story",
                        "entity_id": 1305,
                        "date": "03 Aug 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "content",
                        "children": null,
                        "user": {
                            "id": 5
                            "first_name": "Dima",
                            "last_name": "Platonov",
                            "slug": "dima.platonov",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/230x230.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/32x32.jpg"
                        }
                    },
                    {
                        "id": 313,
                        "entity": "story",
                        "entity_id": 1305,
                        "date": "03 Aug 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "content",
                        "children": null,
                        "user": {
                            "id": 5,
                            "first_name": "Dima",
                            "last_name": "Platonov",
                            "slug": "dima.platonov",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/230x230.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/32x32.jpg"
                        }
                    },
                    {
                        "id": 314,
                        "entity": "story",
                        "entity_id": 1305,
                        "date": "03 Aug 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "0",
                        "children": null,
                        "user": {
                            "id": 5,
                            "first_name": "Dima",
                            "last_name": "Platonov",
                            "slug": "dima.platonov",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/230x230.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/32x32.jpg"
                        }
                    }
                ]
            },
            {
                "id": 1299,
                "text": "<p>234</p>\n",
                "date": {
                    "created": "18 Jul 2017",
                    "exactCreated": "18 Jul 2017 7:07:50",
                    "startedOn": "18 Jul 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
                    "qty": 1,
                    "is_liked": false,
                    "people_list": [
                        {
                            "user": {
                                "id": 53,
                                "fullName": "Darth Vader",
                                "slug": "darth.vader",
                                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/IBKWX_s5-ua0fo55IOLV_PY_ZnQYoN6x.jpg",
                                "is_friend": true,
                                "is_owner": false
                            }
                        }
                    ]
                },
                "comments": [
                    {
                        "id": 91,
                        "entity": "story",
                        "entity_id": 1299,
                        "date": "18 Jul 2017",
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
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 92,
                        "entity": "story",
                        "entity_id": 1299,
                        "date": "18 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "5",
                        "children": null,
                        "user": {
                            "id": 1,
                            "first_name": "Jimbo",
                            "last_name": "Fry",
                            "slug": "jimbo.fry",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 93,
                        "entity": "story",
                        "entity_id": 1299,
                        "date": "18 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "6",
                        "children": null,
                        "user": {
                            "id": 1,
                            "first_name": "Jimbo",
                            "last_name": "Fry",
                            "slug": "jimbo.fry",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 224,
                        "entity": "story",
                        "entity_id": 1299,
                        "date": "25 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "opp",
                        "children": null,
                        "user": {
                            "id": 1,
                            "first_name": "Jimbo",
                            "last_name": "Fry",
                            "slug": "jimbo.fry",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    }
                ]
            },
            {
                "id": 1298,
                "text": "<p>3</p>\n",
                "date": {
                    "created": "18 Jul 2017",
                    "exactCreated": "18 Jul 2017 7:01:42",
                    "startedOn": "18 Jul 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
                    "qty": 2,
                    "is_liked": true,
                    "people_list": [
                        {
                            "user": {
                                "id": 53,
                                "fullName": "Darth Vader",
                                "slug": "darth.vader",
                                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/IBKWX_s5-ua0fo55IOLV_PY_ZnQYoN6x.jpg",
                                "is_friend": true,
                                "is_owner": false
                            }
                        },
                        {
                            "user": {
                                "id": 1,
                                "fullName": "Jimbo Fry",
                                "slug": "jimbo.fry",
                                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                "is_friend": false,
                                "is_owner": true
                            }
                        }
                    ]
                },
                "comments": [
                    {
                        "id": 96,
                        "entity": "story",
                        "entity_id": 1298,
                        "date": "18 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "3",
                        "children": [
                            {
                                "id": 223,
                                "entity": "story",
                                "entity_id": 1298,
                                "date": "25 Jul 2017",
                                "content": "123",
                                "children": null,
                                "user": {
                                    "id": 53,
                                    "first_name": "Darth",
                                    "last_name": "Vader",
                                    "slug": "darth.vader",
                                    "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/FaBvI1iPKJvkSv3MrcSnwZXR2y8t-CrV.jpg",
                                    "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/IBKWX_s5-ua0fo55IOLV_PY_ZnQYoN6x.jpg"
                                }
                            }
                        ],
                        "user": {
                            "id": 1,
                            "first_name": "Jimbo",
                            "last_name": "Fry",
                            "slug": "jimbo.fry",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 97,
                        "entity": "story",
                        "entity_id": 1298,
                        "date": "18 Jul 2017",
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
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 220,
                        "entity": "story",
                        "entity_id": 1298,
                        "date": "24 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "5",
                        "children": null,
                        "user": {
                            "id": 53, 
                            "first_name": "Darth",
                            "last_name": "Vader",
                            "slug": "darth.vader",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/FaBvI1iPKJvkSv3MrcSnwZXR2y8t-CrV.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/IBKWX_s5-ua0fo55IOLV_PY_ZnQYoN6x.jpg"
                        }
                    },
                    {
                        "id": 222,
                        "entity": "story",
                        "entity_id": 1298,
                        "date": "25 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "123",
                        "children": null,
                        "user": {
                            "id": 53,
                            "first_name": "Darth",
                            "last_name": "Vader",
                            "slug": "darth.vader",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/FaBvI1iPKJvkSv3MrcSnwZXR2y8t-CrV.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/IBKWX_s5-ua0fo55IOLV_PY_ZnQYoN6x.jpg"
                        }
                    }
                ]
            },
            {
                "id": 1297,
                "text": "<p>2</p>\n",
                "date": {
                    "created": "18 Jul 2017",
                    "exactCreated": "18 Jul 2017 6:57:52",
                    "startedOn": "18 Jul 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
                    "qty": 1,
                    "is_liked": true,
                    "people_list": [
                        {
                            "user": {
                                "id": 1,
                                "fullName": "Jimbo Fry",
                                "slug": "jimbo.fry",
                                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                "is_friend": false,
                                "is_owner": true
                            }
                        }
                    ]
                },
                "comments": [
                    {
                        "id": 98,
                        "entity": "story",
                        "entity_id": 1297,
                        "date": "18 Jul 2017",
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
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 99,
                        "entity": "story",
                        "entity_id": 1297,
                        "date": "18 Jul 2017",
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
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 100,
                        "entity": "story",
                        "entity_id": 1297,
                        "date": "18 Jul 2017",
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
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 101,
                        "entity": "story",
                        "entity_id": 1297,
                        "date": "18 Jul 2017",
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
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    }
                ]
            },
            {
                "id": 1296,
                "text": "<p>1</p>\n",
                "date": {
                    "created": "17 Jul 2017",
                    "exactCreated": "17 Jul 2017 14:52:51",
                    "startedOn": "17 Jul 2017",
                    "completedOn": null
                },
                "loudness": {
                    "inStoryline": true,
                    "inChannels": true,
                    "inBooks": true
                },
                "visibility": {
                    "value": 1,
                    "users_custom_visibility": []
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
                    "qty": 1,
                    "is_liked": true,
                    "people_list": [
                        {
                            "user": {
                                "id": 1,
                                "fullName": "Jimbo Fry",
                                "slug": "jimbo.fry",
                                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg",
                                "is_friend": false,
                                "is_owner": true
                            }
                        }
                    ]
                },
                "comments": [
                    {
                        "id": 75,
                        "entity": "story",
                        "entity_id": 1296,
                        "date": "18 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "1",
                        "children": [
                            {
                                "id": 80,
                                "entity": "story",
                                "entity_id": 1296,
                                "date": "18 Jul 2017",
                                "content": "1-1",
                                "children": null,
                                "user": {
                                    "id": 1,
                                    "first_name": "Jimbo",
                                    "last_name": "Fry",
                                    "slug": "jimbo.fry",
                                    "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                                    "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                                }
                            }
                        ],
                        "user": {
                            "id": 1,
                            "first_name": "Jimbo",
                            "last_name": "Fry",
                            "slug": "jimbo.fry",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    },
                    {
                        "id": 76,
                        "entity": "story",
                        "entity_id": 1296,
                        "date": "18 Jul 2017",
                        "parent_id": 0,
                        "parent": [],
                        "content": "2",
                        "children": [
                            {
                                "id": 77,
                                "entity": "story",
                                "entity_id": 1296,
                                "date": "18 Jul 2017",
                                "content": "2-1",
                                "children": [
                                    {
                                        "id": 78,
                                        "entity": "story",
                                        "entity_id": 1296,
                                        "date": "18 Jul 2017",
                                        "content": "2-2",
                                        "children": [
                                            {
                                                "id": 79,
                                                "entity": "story",
                                                "entity_id": 1296,
                                                "date": "18 Jul 2017",
                                                "content": "2-3",
                                                "children": null,
                                                "user": {
                                                    "id": 1,
                                                    "first_name": "Jimbo",
                                                    "last_name": "Fry",
                                                    "slug": "jimbo.fry",
                                                    "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                                                    "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                                                }
                                            }
                                        ],
                                        "user": {
                                            "id": 1,
                                            "first_name": "Jimbo",
                                            "last_name": "Fry",
                                            "slug": "jimbo.fry",
                                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                                        }
                                    }
                                ],
                                "user": {
                                    "id": 1,
                                    "first_name": "Jimbo",
                                    "last_name": "Fry",
                                    "slug": "jimbo.fry",
                                    "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                                    "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                                }
                            }
                        ],
                        "user": {
                            "id": 1,
                            "first_name": "Jimbo",
                            "last_name": "Fry",
                            "slug": "jimbo.fry",
                            "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/M7wtvvBxXtYoTDVUnXxny-vfthhQ2FFo.jpg",
                            "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                        }
                    }
                ]
            }
        ]
    }
    ```
 
* **Error Response:**
   * **Code:** 400 Bad Request <br />
   * **Code:** 404 NOT FOUND <br />
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
      url: "/v1/users/john.smith/stories",
      dataType: "json",
      type : "GET",
      data: {page: "2"},
      success : function(r) {
        console.log(r);
      }
    });
  ```