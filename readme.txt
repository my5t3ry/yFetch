default folders/files:
./input -> in this folder are the input files for download items
./output -> here goes the generated output
./config.json -> the main config file


run with php 7.2:
php yFetch.php

switches:
php yFetch.php -f -> only run the download tasks
php yFetch.php -s -> only run the scraping tasks


open tasks:
- date conversion for csv output
- check if some mappings are empty at channel/playlist


config description:
- the meta data csv mapping is defined in this config video-meta-mapping/channel-meta-mapping/playlist-meta-mapping. to add new data take a look at the youtube api objects at the end of this file.


{
  "download-pool-size" : 5 -> how many thumbnails are downloaded at once. default=5
  "videoQualityStrategy": "best" -> only best format is downloaded
  "thumbResolution": "high" -> default (120px wide and 90px tall), medium (320px wide and 180px tall), high (480px wide and 360px tall), standard(640px wide and 480px tall), maxres(1280px wide and 720px tall) is possible
  "access": [
    "private",
    "public",
    "unlisted"
  ], -> list of valide privacy states
  "tags": [
    "classical",
    "folk",
    "piano"
  ],  -> list of valid tags
  "startdate": "2014-01-01",
  "enddate": "2019-01-01",
  "tagCountThreshold": "2",
  "force-override": "true",     -> cleans old download files
  "channelid": "UCv1WDP5EiipMQ__C4Cg6aow",
  "directorySaveStrategy": "bundle",      -> save items as bundle/flat directory structure
  "api-key": "AIzaSyCLYc0OBUVZFzdF8kFmv7bqyfIynd-D8L8",
  "video-csv-name": "/../output/videos.csv",
  "video-csv-delimiter": "\t",
  "channel-csv-name": "/../output/channel.csv",
  "channel-csv-delimiter": ",",
  "playlists-csv-name": "/../output/playlists.csv",
  "playlists-csv-delimiter": ",",
  "video-meta-mapping": {
    "id": "id",
    "publishedAt": "snippet.publishedAt",
    "duration": "contentDetails.duration",
    "privacyStatus": "status.privacyStatus",
    "viewCount": "statistics.viewCount",
    "tagCount": "snippet.tags",
    "title": "snippet.title",
    "thumbUrl": "snippet.thumbnails.(0).url",
    "commentCount": "statistics.commentCount",
    "likeCount": "statistics.likeCount",
    "dislikeCount": "statistics.dislikeCount",
    "favoriteCount": "statistics.favoriteCount",
    "dislikeCount": "statistics.dislikeCount",
    "description": "snippet.description",
    "tags": "snippet.tags",
    "fileName": "fileDetails.fileName",
    "definition": "contentDetails.definition",
    "caption": "contentDetails.caption",
    "definition": "contentDetails.definition",
    "licensedontent": "contentDetails.licensedContent"
  },
  "playlist-meta-mapping": {
    "id": "id",
    "publishedAt": "snippet.publishedAt",
    "duration": "contentDetails.duration",
    "privacyStatus": "status.privacyStatus",
    "viewCount": "statistics.viewCount",
    "tagCount": "snippet.tags",
    "title": "snippet.title",
    "thumbUrl": "snippet.thumbnails.(0).url",
    "commentCount": "statistics.commentCount",
    "likeCount": "statistics.likeCount",
    "dislikeCount": "statistics.dislikeCount",
    "favoriteCount": "statistics.favoriteCount",
    "dislikeCount": "statistics.dislikeCount",
    "description": "snippet.description",
    "tags": "snippet.tags",
    "fileName": "fileDetails.fileName",
    "definition": "contentDetails.definition",
    "caption": "contentDetails.caption",
    "definition": "contentDetails.definition",
    "licensedontent": "contentDetails.licensedContent"
  },
 "channel-meta-mapping": {
    "id": "id",
    "publishedAt": "snippet.publishedAt",
    "duration": "contentDetails.duration",
    "privacyStatus": "status.privacyStatus",
    "viewCount": "statistics.viewCount",
    "tagCount": "snippet.tags",
    "title": "snippet.title",
    "thumbUrl": "snippet.thumbnails.(0).url",
    "commentCount": "statistics.commentCount",
    "likeCount": "statistics.likeCount",
    "dislikeCount": "statistics.dislikeCount",
    "favoriteCount": "statistics.favoriteCount",
    "dislikeCount": "statistics.dislikeCount",
    "description": "snippet.description",
    "tags": "snippet.tags",
    "fileName": "fileDetails.fileName",
    "definition": "contentDetails.definition",
    "caption": "contentDetails.caption",
    "definition": "contentDetails.definition",
    "licensecontent": "contentDetails.licensedContent"
  }
}







video obj ->
{
  "kind": "youtube#video",
  "etag": etag,
  "id": string,
  "snippet": {
    "publishedAt": datetime,
    "channelId": string,
    "title": string,
    "description": string,
    "thumbnails": {
      (key): {
        "url": string,
        "width": unsigned integer,
        "height": unsigned integer
      }
    },
    "channelTitle": string,
    "tags": [
      string
    ],
    "categoryId": string,
    "liveBroadcastContent": string,
    "defaultLanguage": string,
    "localized": {
      "title": string,
      "description": string
    },
    "defaultAudioLanguage": string
  },
  "contentDetails": {
    "duration": string,
    "dimension": string,
    "definition": string,
    "caption": string,
    "licensedContent": boolean,
    "regionRestriction": {
      "allowed": [
        string
      ],
      "blocked": [
        string
      ]
    },
    "contentRating": {
      "acbRating": string,
      "agcomRating": string,
      "anatelRating": string,
      "bbfcRating": string,
      "bfvcRating": string,
      "bmukkRating": string,
      "catvRating": string,
      "catvfrRating": string,
      "cbfcRating": string,
      "cccRating": string,
      "cceRating": string,
      "chfilmRating": string,
      "chvrsRating": string,
      "cicfRating": string,
      "cnaRating": string,
      "cncRating": string,
      "csaRating": string,
      "cscfRating": string,
      "czfilmRating": string,
      "djctqRating": string,
      "djctqRatingReasons": [,
        string
      ],
      "ecbmctRating": string,
      "eefilmRating": string,
      "egfilmRating": string,
      "eirinRating": string,
      "fcbmRating": string,
      "fcoRating": string,
      "fmocRating": string,
      "fpbRating": string,
      "fpbRatingReasons": [,
        string
      ],
      "fskRating": string,
      "grfilmRating": string,
      "icaaRating": string,
      "ifcoRating": string,
      "ilfilmRating": string,
      "incaaRating": string,
      "kfcbRating": string,
      "kijkwijzerRating": string,
      "kmrbRating": string,
      "lsfRating": string,
      "mccaaRating": string,
      "mccypRating": string,
      "mcstRating": string,
      "mdaRating": string,
      "medietilsynetRating": string,
      "mekuRating": string,
      "mibacRating": string,
      "mocRating": string,
      "moctwRating": string,
      "mpaaRating": string,
      "mpaatRating": string,
      "mtrcbRating": string,
      "nbcRating": string,
      "nbcplRating": string,
      "nfrcRating": string,
      "nfvcbRating": string,
      "nkclvRating": string,
      "oflcRating": string,
      "pefilmRating": string,
      "rcnofRating": string,
      "resorteviolenciaRating": string,
      "rtcRating": string,
      "rteRating": string,
      "russiaRating": string,
      "skfilmRating": string,
      "smaisRating": string,
      "smsaRating": string,
      "tvpgRating": string,
      "ytRating": string
    },
    "projection": string,
    "hasCustomThumbnail": boolean
  },
  "status": {
    "uploadStatus": string,
    "failureReason": string,
    "rejectionReason": string,
    "privacyStatus": string,
    "publishAt": datetime,
    "license": string,
    "embeddable": boolean,
    "publicStatsViewable": boolean
  },
  "statistics": {
    "viewCount": unsigned long,
    "likeCount": unsigned long,
    "dislikeCount": unsigned long,
    "favoriteCount": unsigned long,
    "commentCount": unsigned long
  },
  "player": {
    "embedHtml": string,
    "embedHeight": long,
    "embedWidth": long
  },
  "topicDetails": {
    "topicIds": [
      string
    ],
    "relevantTopicIds": [
      string
    ],
    "topicCategories": [
      string
    ]
  },
  "recordingDetails": {
    "recordingDate": datetime
  },
  "fileDetails": {
    "fileName": string,
    "fileSize": unsigned long,
    "fileType": string,
    "container": string,
    "videoStreams": [
      {
        "widthPixels": unsigned integer,
        "heightPixels": unsigned integer,
        "frameRateFps": double,
        "aspectRatio": double,
        "codec": string,
        "bitrateBps": unsigned long,
        "rotation": string,
        "vendor": string
      }
    ],
    "audioStreams": [
      {
        "channelCount": unsigned integer,
        "codec": string,
        "bitrateBps": unsigned long,
        "vendor": string
      }
    ],
    "durationMs": unsigned long,
    "bitrateBps": unsigned long,
    "creationTime": string
  },
  "processingDetails": {
    "processingStatus": string,
    "processingProgress": {
      "partsTotal": unsigned long,
      "partsProcessed": unsigned long,
      "timeLeftMs": unsigned long
    },
    "processingFailureReason": string,
    "fileDetailsAvailability": string,
    "processingIssuesAvailability": string,
    "tagSuggestionsAvailability": string,
    "editorSuggestionsAvailability": string,
    "thumbnailsAvailability": string
  },
  "suggestions": {
    "processingErrors": [
      string
    ],
    "processingWarnings": [
      string
    ],
    "processingHints": [
      string
    ],
    "tagSuggestions": [
      {
        "tag": string,
        "categoryRestricts": [
          string
        ]
      }
    ],
    "editorSuggestions": [
      string
    ]
  },
  "liveStreamingDetails": {
    "actualStartTime": datetime,
    "actualEndTime": datetime,
    "scheduledStartTime": datetime,
    "scheduledEndTime": datetime,
    "concurrentViewers": unsigned long,
    "activeLiveChatId": string
  },
  "localizations": {
    (key): {
      "title": string,
      "description": string
    }
  }
}

playlist obj ->
{
  "kind": "youtube#playlist",
  "etag": etag,
  "id": string,
  "snippet": {
    "publishedAt": datetime,
    "channelId": string,
    "title": string,
    "description": string,
    "thumbnails": {
      (key): {
        "url": string,
        "width": unsigned integer,
        "height": unsigned integer
      }
    },
    "channelTitle": string,
    "tags": [
      string
    ],
    "defaultLanguage": string,
    "localized": {
      "title": string,
      "description": string
    }
  },
  "status": {
    "privacyStatus": string
  },
  "contentDetails": {
    "itemCount": unsigned integer
  },
  "player": {
    "embedHtml": string
  },
  "localizations": {
    (key): {
      "title": string,
      "description": string
    }
  }
}



 "channel-meta-mapping": {
    "id": "id",
    "publishedAt": "snippet.publishedAt",
    "duration": "contentDetails.duration",
    "privacyStatus": "status.privacyStatus",
    "playlist-likes": "contentDetails.relatedPlaylists.likes",
    "playlist-favorites": "contentDetails.relatedPlaylists.favorites",
    "playlist-uploads": "contentDetails.relatedPlaylists.uploads",
    "playlist-watchHistory": "contentDetails.relatedPlaylists.watchHistory",
    "playlist-watchLater": "contentDetails.relatedPlaylists.watchLater",
    "viewCount": "statistics.viewCount",
    "commentCount": "statistics.commentCount",
    "subscriberCount": "statistics.subscriberCount",
    "hiddenSubscriberCount": "statistics.hiddenSubscriberCount",
    "videoCount": "statistics.videoCount",
    "title": "snippet.title",
    "description": "snippet.description",
    "thumbUrl": "snippet.thumbnails.(0).url",
    "definition": "contentDetails.definition",
  }
channel obj ->
{
  "kind": "youtube#channel",
  "etag": etag,
  "id": string,
  "snippet": {
    "title": string,
    "description": string,
    "customUrl": string,
    "publishedAt": datetime,
    "thumbnails": {
      (key): {
        "url": string,
        "width": unsigned integer,
        "height": unsigned integer
      }
    },
    "defaultLanguage": string,
    "localized": {
      "title": string,
      "description": string
    },
    "country": string
  },
  "contentDetails": {
    "relatedPlaylists": {
      "likes": string,
      "favorites": string,
      "uploads": string,
      "watchHistory": string,
      "watchLater": string
    }
  },
  "statistics": {
    "viewCount": unsigned long,
    "commentCount": unsigned long,
    "subscriberCount": unsigned long,
    "hiddenSubscriberCount": boolean,
    "videoCount": unsigned long
  },
  "topicDetails": {
    "topicIds": [
      string
    ],
    "topicCategories": [
      string
    ]
  },
  "status": {
    "privacyStatus": string,
    "isLinked": boolean,
    "longUploadsStatus": string
  },
  "brandingSettings": {
    "channel": {
      "title": string,
      "description": string,
      "keywords": string,
      "defaultTab": string,
      "trackingAnalyticsAccountId": string,
      "moderateComments": boolean,
      "showRelatedChannels": boolean,
      "showBrowseView": boolean,
      "featuredChannelsTitle": string,
      "featuredChannelsUrls": [
        string
      ],
      "unsubscribedTrailer": string,
      "profileColor": string,
      "defaultLanguage": string,
      "country": string
    },
    "watch": {
      "textColor": string,
      "backgroundColor": string,
      "featuredPlaylistId": string
    },
    "image": {
      "bannerImageUrl": string,
      "bannerMobileImageUrl": string,
      "watchIconImageUrl": string,
      "trackingImageUrl": string,
      "bannerTabletLowImageUrl": string,
      "bannerTabletImageUrl": string,
      "bannerTabletHdImageUrl": string,
      "bannerTabletExtraHdImageUrl": string,
      "bannerMobileLowImageUrl": string,
      "bannerMobileMediumHdImageUrl": string,
      "bannerMobileHdImageUrl": string,
      "bannerMobileExtraHdImageUrl": string,
      "bannerTvImageUrl": string,
      "bannerTvLowImageUrl": string,
      "bannerTvMediumImageUrl": string,
      "bannerTvHighImageUrl": string,
      "bannerExternalUrl": string
    },
    "hints": [
      {
        "property": string,
        "value": string
      }
    ]
  },
  "invideoPromotion": {
    "defaultTiming": {
      "type": string,
      "offsetMs": unsigned long,
      "durationMs": unsigned long
    },
    "position": {
      "type": string,
      "cornerPosition": string
    },
    "items": [
      {
        "id": {
          "type": string,
          "videoId": string,
          "websiteUrl": string,
          "recentlyUploadedBy": string
        },
        "timing": {
          "type": string,
          "offsetMs": unsigned long,
          "durationMs": unsigned long
        },
        "customMessage": string,
        "promotedByContentOwner": boolean
      }
    ],
    "useSmartTiming": boolean
  },
  "auditDetails": {
    "overallGoodStanding": boolean,
    "communityGuidelinesGoodStanding": boolean,
    "copyrightStrikesGoodStanding": boolean,
    "contentIdClaimsGoodStanding": boolean
  },
  "contentOwnerDetails": {
    "contentOwner": string,
    "timeLinked": datetime
  },
  "localizations": {
    (key): {
      "title": string,
      "description": string
    }
  }
}





autor notes  ->sudo apt-get install php-curl

$date = new DateTime('1970-01-01');
$date->add(new DateInterval('PT2H34M25S'));
echo $date->format('H:i:s')