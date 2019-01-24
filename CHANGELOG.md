# Revision History

## 0.0

### 0.11.0 (2019-01-23)

* Returns a Result object on success

#### Backwards-incompatible changes

* The Analytics\Client returns an Analytics\Result instead of an array of JSON. You can get the same results as before by calling the getDecoded() method on the Result object.