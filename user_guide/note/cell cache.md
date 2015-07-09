## cell
* 位置在 application/cell/
* controller 命名皆需加上 **_cells**[^1] 後綴字
* method 加上前綴字 **\_cache_**[^2] 即可開啟 cache 的功能
* view 格式為 `controller_name/method_name.php`
* cache 預設格式為 `_cell_|_controller_name_|_method_name`，預設前綴字格式為 **_cell**[^3]
* 所有的 cell 相關設定接在	 `application/config/system/cell.php`，Library 則是 `application/libraries/cell.php`，在使用 `application/helpers/cell_helper.php` 讓其功能可被任意位置使用。


## Sample

* 使用 main_cells 內的 index

	```
  echo render_cell ('main_cells', 'index');
```


* controllers/main_cells.php 內容

	```
  class Main_cells extends Cell_Controller {

    public function _cache_index () {
      return array ('time' => 60 * 60, 'key' => null);
    }
    public function index () {
      return $this->load_view ();
    }
  }
```

* views/main_cells/index.php

	```
  <p>Hello!</p>
```

* cache 檔案位置 `cache/_cell_|_main_cells_|_index`


[^1]: cell 的 controller 後綴字設定可至 `application/config/system/cell.php` 內修改  **class_suffix** 變數

[^2]: 開啟 cache 的前綴字設定可至 `application/config/system/cell.php` 內修改 **method_prefix** 變數

[^3]: cache 檔案的前綴字設定可至 `application/config/system/cell.php` 內修改 **file_prefix** 變數