## post

* 沒接到東西或失敗，皆回傳 null

### 一般

* Html

	```
  <form method="post">
    <input type='text' name='account' value='oa' />
  </form>
```

* Submit 後的 php

	```
  $account = $this->input_post ('account');
```


### 陣列

* Html

	```
  <form method="post">
    <input type='text' name='items[]' value='a' />
    <input type='text' name='items[]' value='b' /> 
    <input type='text' name='items[]' value='c' />
    <input type='text' name='items[]' value='d' />
  </form>
```

* Submit 後的 php

	```
  $items = $this->input_post ('items');
```

### 圖片

* Html

	```
  <form method="post" enctype="multipart/form-data">
    <input type='file' name='pic' value=''/>
  </form>
```

* Submit 後的 php

	```
  $pic = $this->input_post ('pic', true, true);
```

### 圖片陣列

* Html

	```
  <form method="post" enctype="multipart/form-data">
    <input type='file' name='pics[]' value=''/>
    <input type='file' name='pics[]' value=''/>
    <input type='file' name='pics[]' value=''/>
    <input type='file' name='pics[]' value=''/>
  </form>
```

* Submit 後的 php

	```
  $pics = $this->input_post ('pics[]', true, true);
```

### 圖片與ORM


* Html

	```
  <form method="post" enctype="multipart/form-data">
      <input type='file' name='pic' value=''/>
  </form>
```

* Model

	```
  class Picture extends OaModel {

    static $table_name = 'pictures';

    public function __construct ($as = array (), $gas = true, $ivf = false, $nr = true) {
      parent::__construct ($as, $gas, $ivf, $nr);
    
      OrmImageUploader::bind ('file_name');
    }
  }
```

* third_party 內的 Model Uploader

	```
  class PictureUploader extends OrmImageUploader {

    public function getVersions () {
      return array (
          '' => array (),
          '50x50' => array ('resize', 50, 50, 'width'),
          '120x120' => array ('adaptiveResizeQuadrant', 120, 120, 'c')
        );
    }
}
```


* Submit 後的 php

	```
  $pic = $this->input_post ('pic', true, true);

  $picture = Picture::create (array ('file_name' => ''));
  $picture->file_name->put ($pic);
```
