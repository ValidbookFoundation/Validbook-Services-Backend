**Get Story**
----
  Returns json data about a book tree where story was logged.

* **URL**

  /v1/stories/`story_id`/books-tree

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
   
  `story_id=[integer]`

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
                "name": "root",
                "key": "root",
                "show": true,
                "children": [
                    {
                        "name": "Wallbook",
                        "key": "65-wallbook",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=65-wallbook",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": true
                    },
                    {
                        "name": "Life",
                        "key": "148-life",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=148-life",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": []
                    },
                    {
                        "name": "Closed Book",
                        "key": "147-closed-book",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=147-closed-book",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": []
                    },
                    {
                        "name": "Ask me anything",
                        "key": "146-ask-me-anything",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=146-ask-me-anything",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": []
                    },
                    {
                        "name": "Karma",
                        "key": "145-karma",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=145-karma",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": []
                    },
                    {
                        "name": "Interests",
                        "key": "66-interests",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=66-interests",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": [
                            {
                                "name": "Beautiful Photos",
                                "key": "144-beautiful-photos",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=144-beautiful-photos",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Ecommerce",
                                "key": "143-ecommerce",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=143-ecommerce",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "I never knew",
                                "key": "142-i-never-knew",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=142-i-never-knew",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Fun",
                                "key": "141-fun",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=141-fun",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Marketing and Promotion",
                                "key": "139-marketing-and-promotion",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=139-marketing-and-promotion",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Youtube",
                                        "key": "140-youtube",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=140-youtube",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Beautiful Nature",
                                "key": "138-beautiful-nature",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=138-beautiful-nature",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Images",
                                "key": "136-images",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=136-images",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Space",
                                        "key": "137-space",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=137-space",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Product Design",
                                "key": "135-product-design",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=135-product-design",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "People management",
                                "key": "134-people-management",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=134-people-management",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Design",
                                "key": "132-design",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=132-design",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "UX design",
                                        "key": "133-ux-design",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=133-ux-design",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Thoughts",
                                "key": "131-thoughts",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=131-thoughts",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Ecology",
                                "key": "130-ecology",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=130-ecology",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Cooperation",
                                "key": "125-cooperation",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=125-cooperation",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Traditions",
                                        "key": "129-traditions",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=129-traditions",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Governance",
                                        "key": "128-governance",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=128-governance",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Game theory",
                                        "key": "127-game-theory",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=127-game-theory",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Open Source",
                                        "key": "126-open-source",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=126-open-source",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Natural Science",
                                "key": "122-natural-science",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=122-natural-science",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Physics",
                                        "key": "124-physics",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=124-physics",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Biology",
                                        "key": "123-biology",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=123-biology",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Reputation",
                                "key": "120-reputation",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=120-reputation",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "To Read",
                                        "key": "121-to-read",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=121-to-read",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "English Learning",
                                "key": "119-english-learning",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=119-english-learning",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "IoT (Internet of Things)",
                                "key": "118-iot-internet-of-things",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=118-iot-internet-of-things",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Artificial Intelligence",
                                "key": "116-artificial-intelligence",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=116-artificial-intelligence",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Turing test",
                                        "key": "117-turing-test",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=117-turing-test",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Science Fiction",
                                "key": "113-science-fiction",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=113-science-fiction",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Ian M Banks",
                                        "key": "114-ian-m-banks",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=114-ian-m-banks",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Favourite Culture Spaceship Names",
                                                "key": "115-favourite-culture-spaceship-names",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=115-favourite-culture-spaceship-names",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                "name": "Social Networks Analysis",
                                "key": "110-social-networks-analysis",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=110-social-networks-analysis",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Facebook",
                                        "key": "112-facebook",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=112-facebook",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Twitter",
                                        "key": "111-twitter",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=111-twitter",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Potpourri",
                                "key": "109-potpourri",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=109-potpourri",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Economics",
                                "key": "100-economics",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=100-economics",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Finance",
                                        "key": "108-finance",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=108-finance",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Property",
                                        "key": "107-property",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=107-property",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Universal Basic Income",
                                        "key": "106-universal-basic-income",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=106-universal-basic-income",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Evolutionary economics",
                                        "key": "105-evolutionary-economics",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=105-evolutionary-economics",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Maltusian trap",
                                        "key": "104-maltusian-trap",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=104-maltusian-trap",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Money: origin, understanding",
                                        "key": "103-money-origin-understanding",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=103-money-origin-understanding",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Value – measurement, understanding",
                                        "key": "102-value-measurement-understanding",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=102-value-measurement-understanding",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "The tragedy of commons",
                                        "key": "101-the-tragedy-of-commons",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=101-the-tragedy-of-commons",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Evolution",
                                "key": "98-evolution",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=98-evolution",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Human Evolution",
                                        "key": "99-human-evolution",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=99-human-evolution",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Psychology",
                                "key": "87-psychology",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=87-psychology",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Altruism",
                                        "key": "97-altruism",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=97-altruism",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Status",
                                        "key": "96-status",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=96-status",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Identity",
                                        "key": "95-identity",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=95-identity",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Needs and motivations",
                                        "key": "94-needs-and-motivations",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=94-needs-and-motivations",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Economics and Psychology",
                                        "key": "93-economics-and-psychology",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=93-economics-and-psychology",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Evolutionary psychology",
                                        "key": "92-evolutionary-psychology",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=92-evolutionary-psychology",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Psychology of social networks",
                                        "key": "91-psychology-of-social-networks",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=91-psychology-of-social-networks",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Lifestyle hacks",
                                        "key": "90-lifestyle-hacks",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=90-lifestyle-hacks",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Habits",
                                        "key": "89-habits",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=89-habits",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Procrastination",
                                        "key": "88-procrastination",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=88-procrastination",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Quotes and credos",
                                "key": "86-quotes-and-credos",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=86-quotes-and-credos",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "All things startup",
                                "key": "85-all-things-startup",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=85-all-things-startup",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Music",
                                "key": "81-music",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=81-music",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Rock Music",
                                        "key": "83-rock-music",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=83-rock-music",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Covers",
                                                "key": "84-covers",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=84-covers",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            }
                                        ]
                                    },
                                    {
                                        "name": "Concentration Music",
                                        "key": "82-concentration-music",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=82-concentration-music",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "QS (Quantified Self)",
                                "key": "80-qs-quantified-self",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=80-qs-quantified-self",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Trustworthy computing",
                                "key": "72-trustworthy-computing",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=72-trustworthy-computing",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Colored Coins",
                                        "key": "79-colored-coins",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=79-colored-coins",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Identity",
                                        "key": "78-identity",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=78-identity",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Cryptocurrencies",
                                        "key": "75-cryptocurrencies",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=75-cryptocurrencies",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Ethereum",
                                                "key": "77-ethereum",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=77-ethereum",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            },
                                            {
                                                "name": "Bitcoin",
                                                "key": "76-bitcoin",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=76-bitcoin",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            }
                                        ]
                                    },
                                    {
                                        "name": "Cryptology",
                                        "key": "74-cryptology",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=74-cryptology",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Smart Contracts",
                                        "key": "73-smart-contracts",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=73-smart-contracts",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Open badges and credentials",
                                "key": "71-open-badges-and-credentials",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=71-open-badges-and-credentials",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Futurism",
                                "key": "70-futurism",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=70-futurism",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Writing",
                                "key": "69-writing",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=69-writing",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Reading",
                                "key": "67-reading",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=67-reading",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Serious books",
                                        "key": "68-serious-books",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=68-serious-books",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "name": "Example Books",
                        "key": "46-example-books",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=46-example-books",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": [
                            {
                                "name": "Voting book (example)",
                                "key": "64-voting-book-example",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=64-voting-book-example",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Business (example)",
                                "key": "56-business-example",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=56-business-example",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Coordination book",
                                        "key": "60-coordination-book",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=60-coordination-book",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Done",
                                                "key": "63-done",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=63-done",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            },
                                            {
                                                "name": "Doing",
                                                "key": "62-doing",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=62-doing",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            },
                                            {
                                                "name": "To Do",
                                                "key": "61-to-do",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=61-to-do",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            }
                                        ]
                                    },
                                    {
                                        "name": "Open project book",
                                        "key": "58-open-project-book",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=58-open-project-book",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Representative book",
                                                "key": "59-representative-book",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=59-representative-book",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            }
                                        ]
                                    },
                                    {
                                        "name": "Contract book (example)",
                                        "key": "57-contract-book-example",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=57-contract-book-example",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Personal (example)",
                                "key": "47-personal-example",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=47-personal-example",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Workout Journal (example)",
                                        "key": "55-workout-journal-example",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=55-workout-journal-example",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Diet journal (example)",
                                        "key": "54-diet-journal-example",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=54-diet-journal-example",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Diary (example)",
                                        "key": "53-diary-example",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=53-diary-example",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Biometric books  (example)",
                                        "key": "48-biometric-books-example",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=48-biometric-books-example",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Coffeine intake",
                                                "key": "52-coffeine-intake",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=52-coffeine-intake",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            },
                                            {
                                                "name": "Sleep",
                                                "key": "51-sleep",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=51-sleep",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            },
                                            {
                                                "name": "Steps",
                                                "key": "50-steps",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=50-steps",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            },
                                            {
                                                "name": "Pulse",
                                                "key": "49-pulse",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=49-pulse",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": []
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "name": "Community activities",
                        "key": "42-community-activities",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=42-community-activities",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": [
                            {
                                "name": "Ecology, Global Warming Prevention Activities",
                                "key": "45-ecology-global-warming-prevention-activities",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=45-ecology-global-warming-prevention-activities",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Community activities (politics)",
                                "key": "44-community-activities-politics",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=44-community-activities-politics",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Wikipedia edits",
                                "key": "43-wikipedia-edits",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=43-wikipedia-edits",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            }
                        ]
                    },
                    {
                        "name": "Life",
                        "key": "41-life",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=41-life",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": []
                    },
                    {
                        "name": "Validbook Books",
                        "key": "27-validbook-books",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=27-validbook-books",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": true,
                        "no_drag": false,
                        "children": [
                            {
                                "name": "Validbook Governance and Funding Strategy",
                                "key": "40-validbook-governance-and-funding-strategy",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=40-validbook-governance-and-funding-strategy",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Validbook's Explainer",
                                "key": "39-validbook-s-explainer",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=39-validbook-s-explainer",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Experts Wanted",
                                "key": "38-experts-wanted",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=38-experts-wanted",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Validbook Development",
                                "key": "32-validbook-development",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=32-validbook-development",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "Validbook Dev Anouncements",
                                        "key": "37-validbook-dev-anouncements",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=37-validbook-dev-anouncements",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Validbook Research",
                                        "key": "35-validbook-research",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=35-validbook-research",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": [
                                            {
                                                "name": "Channels: algorithms, approaches",
                                                "key": "36-channels-algorithms-approaches",
                                                "icon": "public",
                                                "href": "http://validbook-api.local/v1/books?book_slug=36-channels-algorithms-approaches",
                                                "auto_export": 1,
                                                "auto_import": 1,
                                                "is_logged_story": false,
                                                "no_drag": false,
                                                "children": [
                                                    {
                                                        "name": "Test book",
                                                        "key": "429-test-book",
                                                        "icon": "public",
                                                        "href": "http://validbook-api.local/v1/books?book_slug=429-test-book",
                                                        "auto_export": 1,
                                                        "auto_import": 1,
                                                        "is_logged_story": false,
                                                        "no_drag": false,
                                                        "children": []
                                                    }
                                                ]
                                            }
                                        ]
                                    },
                                    {
                                        "name": "Validbook Dev History",
                                        "key": "34-validbook-dev-history",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=34-validbook-dev-history",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Validbook Dev Plans",
                                        "key": "33-validbook-dev-plans",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=33-validbook-dev-plans",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": false,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "name": "Validbook Feedback",
                                "key": "28-validbook-feedback",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=28-validbook-feedback",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": true,
                                "no_drag": false,
                                "children": [
                                    {
                                        "name": "People Representatives book (shadow 1)",
                                        "key": "31-people-representatives-book-shadow-1",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=31-people-representatives-book-shadow-1",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": true,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "People Representatives book (official)",
                                        "key": "30-people-representatives-book-official",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=30-people-representatives-book-official",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": true,
                                        "no_drag": false,
                                        "children": []
                                    },
                                    {
                                        "name": "Ideas for templates of books",
                                        "key": "29-ideas-for-templates-of-books",
                                        "icon": "public",
                                        "href": "http://validbook-api.local/v1/books?book_slug=29-ideas-for-templates-of-books",
                                        "auto_export": 1,
                                        "auto_import": 1,
                                        "is_logged_story": true,
                                        "no_drag": false,
                                        "children": []
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "name": "Karma Books",
                        "key": "23-karma-books",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=23-karma-books",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": [
                            {
                                "name": "Public Karma",
                                "key": "26-public-karma",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=26-public-karma",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Closed Karma",
                                "key": "25-closed-karma",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=25-closed-karma",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            },
                            {
                                "name": "Open Karma",
                                "key": "24-open-karma",
                                "icon": "public",
                                "href": "http://validbook-api.local/v1/books?book_slug=24-open-karma",
                                "auto_export": 1,
                                "auto_import": 1,
                                "is_logged_story": false,
                                "no_drag": false,
                                "children": []
                            }
                        ]
                    },
                    {
                        "name": "New Book",
                        "key": "373-new-book",
                        "icon": "public",
                        "href": "http://validbook-api.local/v1/books?book_slug=373-new-book",
                        "auto_export": 1,
                        "auto_import": 1,
                        "is_logged_story": false,
                        "no_drag": false,
                        "children": []
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
      url: "/v1/stories/1332/books-tree",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```