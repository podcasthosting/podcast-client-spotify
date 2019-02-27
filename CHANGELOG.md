# Revision History

## 0.0

### 0.11.0 (2019-01-23)

* Returns a Result object on success

#### Backwards-incompatible changes

* The Analytics\Client returns an Analytics\Result instead of an array of JSON. You can get the same results as before by calling the getDecoded() method on the Result object.

### 0.11.1 (2019-02-18)

* Method signature for psr/http-client v1.0.0 methods changed

### 0.12 (2019-02-27)

* Added new methods to retrieve status and update uri as documented for Spotify Podcast Ingestion API - v.1.5  
* Added parameter for passthrough option
* Re-introduced DuplicateException to reflect changes with duplicate titles