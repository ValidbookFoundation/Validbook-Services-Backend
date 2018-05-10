**Stories search**
----
    Returns json data about result of stories search.
    Sorting
    1. show friends stories (I following, me following)
    2. show followed users stories
    3. other stories

* **URL**

    v1/search/stories:q

* **Method:**

    `GET`

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
           "friendsAndFollowing": [
               {
                   "id": 763,
                   "text": "A way to relax after work. Found this leaving the office today - with people jumping from one button to another :) #fun #lumiere",
                   "date": {
                       "created": "12 Jan 2016",
                       "exactCreated": "12 Jan 2016 20:27:26",
                       "startedOn": "12 Jan 2016",
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
                       "id": 2,
                       "fullName": "Olga Sochneva",
                       "slug": "olga.sochneva",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                   },
                   "images": [
                       "https://s3-us-west-2.amazonaws.com/dev.validbook/stories/2017/06/20/763/XeVZlPDHZNB0PBCL0yqveZ8v6bxEgo7O.jpg",
                       "https://s3-us-west-2.amazonaws.com/dev.validbook/stories/2017/06/20/763/5Slowd_BHYeXdiZb7QJ4gVwVrwTAYE9i.jpg"
                   ],
                   "links": [],
                   "books": [
                       {
                           "id": 152,
                           "name": "Interests",
                           "slug": null
                       },
                       {
                           "id": 156,
                           "name": "Fun",
                           "slug": null
                       },
                       {
                           "id": 162,
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
                   "id": 747,
                   "text": "Write Copy for 'Work With Me' page and update the page by July, 15 #CopyWriting, #BuildingWebsite",
                   "date": {
                       "created": "30 Jun 2015",
                       "exactCreated": "30 Jun 2015 11:35:56",
                       "startedOn": "30 Jun 2015",
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
                       "id": 2,
                       "fullName": "Olga Sochneva",
                       "slug": "olga.sochneva",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                   },
                   "images": [],
                   "links": [],
                   "books": [
                       {
                           "id": 162,
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
                   "id": 715,
                   "text": "I'll test how this plan feature works",
                   "date": {
                       "created": "26 May 2015",
                       "exactCreated": "26 May 2015 17:46:10",
                       "startedOn": "26 May 2015",
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
                       "id": 2,
                       "fullName": "Olga Sochneva",
                       "slug": "olga.sochneva",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                   },
                   "images": [],
                   "links": [],
                   "books": [
                       {
                           "id": 162,
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
                   "id": 1223,
                   "text": "<p>React-Redux server rendering. How it works?<a href=\"https://hackernoon.com/isomorphic-universal-boilerplate-react-redux-server-rendering-tutorial-example-webpack-compenent-6e22106ae285\" rel=\"external nofollow\" target=\"_blank\">https://hackernoon.com/isomorphic-universal-boilerplate-react-redux-server-rendering-tutorial-example-webpack-compenent-6e22106ae285</a></p>",
                   "date": {
                       "created": "28 Apr 2017",
                       "exactCreated": "28 Apr 2017 6:45:13",
                       "startedOn": "28 Apr 2017",
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
                       "id": 4,
                       "fullName": "Alex Tykhonchuk",
                       "slug": "alex.tykhonchuk",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                   },
                   "images": [],
                   "links": [
                       {
                           "id": 337,
                           "link": "https://hackernoon.com/isomorphic-universal-boilerplate-react-redux-server-rendering-tutorial-example-webpack-compenent-6e22106ae285",
                           "title": "Break Down Isomorphic and Universal Boilerplate: React-Redux server rendering",
                           "image_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/stories/2017/06/20/1223/ayhjYwlhPfA81lok_s2sThyoTxu5G2Su.jpg",
                           "description": "This is a tutorial with an example to show how react-redux connected in both component and data flow.",
                           "twitter_author": "",
                           "twitter_avatar": ""
                       }
                   ],
                   "books": [
                       {
                           "id": 241,
                           "name": "Wallbook",
                           "slug": null
                       },
                       {
                           "id": 243,
                           "name": "Developing",
                           "slug": null
                       },
                       {
                           "id": 263,
                           "name": "Validson Development",
                           "slug": null
                       },
                       {
                           "id": 264,
                           "name": "Validbook Development",
                           "slug": null
                       },
                       {
                           "id": 265,
                           "name": "Node.js & React",
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
                   "id": 1145,
                   "text": "<p><a href=\"https://github.com/roman01la/react-flux-workshop\" rel=\"external nofollow\" target=\"_blank\">https://github.com/roman01la/react-flux-workshop</a></p>",
                   "date": {
                       "created": "04 Feb 2017",
                       "exactCreated": "04 Feb 2017 12:10:01",
                       "startedOn": "04 Feb 2017",
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
                       "id": 4,
                       "fullName": "Alex Tykhonchuk",
                       "slug": "alex.tykhonchuk",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                   },
                   "images": [],
                   "links": [
                       {
                           "id": 278,
                           "link": "https://github.com/roman01la/react-flux-workshop",
                           "title": "roman01la/react-flux-workshop",
                           "image_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/stories/2017/06/20/1145/FDDQhH8WmfKGgU8XjftIDQ1YsXSKDcum.jpg",
                           "description": "react-flux-workshop - Материалы к воркшопу «React.js и архитектура Flux»",
                           "twitter_author": "",
                           "twitter_avatar": ""
                       }
                   ],
                   "books": [
                       {
                           "id": 254,
                           "name": "JS Frameworks",
                           "slug": null
                       },
                       {
                           "id": 255,
                           "name": "React.js",
                           "slug": null
                       }
                   ],
                   "likes": {
                       "qty": 0,
                       "is_liked": false,
                       "people_list": []
                   },
                   "comments": []
               }
           ],
           "other": [
               {
                   "id": 1020,
                   "text": "Worked masters!",
                   "date": {
                       "created": "14 Feb 2016",
                       "exactCreated": "14 Feb 2016 15:02:36",
                       "startedOn": "14 Feb 2016",
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
                       "id": 3,
                       "fullName": "Denis Dragomirik",
                       "slug": "denis.dragomirik",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/3/32x32.jpg"
                   },
                   "images": [
                       "https://s3-us-west-2.amazonaws.com/dev.validbook/stories/2017/06/20/1020/4LavNB5J_KLh1epGScIlHZLcbVnNhYlf.jpg"
                   ],
                   "links": [],
                   "books": [
                       {
                           "id": 189,
                           "name": "Work log",
                           "slug": null
                       }
                   ],
                   "likes": {
                       "qty": 0,
                       "is_liked": false,
                       "people_list": []
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
        url: "/v1/search/stories?q=a",
        dataType: "json",
        type : "GET",
        data: {q: "work"},
        success : function(r) {
            console.log(r);
        }
    });
    ```