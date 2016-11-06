  /**
   * @api {get} https://works.ioa.tw/weather/api/all.json 01_取得所有縣市鄉鎮
   * @apiGroup Weather API
   *
   *
   * @apiSuccess {Number}   id            縣市 ID
   * @apiSuccess {String}   name          縣市 名稱
   * @apiSuccess {Array}    towns         該縣市內的鄉鎮
   * @apiSuccess {Number}   towns.id      鄉鎮 ID
   * @apiSuccess {String}   towns.name    鄉鎮 名稱
   *
   * @apiSuccessExample {json} Success Response:
   *     HTTP/1.1 200 OK
   *     [
   *         {
   *             "id": "1",
   *             "name": "台北市",
   *             "towns": [
   *                 {
   *                     "id": "1",
   *                     "name": "中正區"
   *                 }
   *             ]
   *         }
   *     ]
   */

  /**
   * @api {get} https://works.ioa.tw/weather/api/cates/:id.json 02_取得指定縣市所有鄉鎮
   * @apiGroup Weather API
   *
   * @apiParam {Number}     id                 縣市 ID
   *
   * @apiSuccess {Number}   id                 縣市 ID
   * @apiSuccess {String}   name               縣市 名稱
   * @apiSuccess {Array}    towns              該縣市內的鄉鎮
   * @apiSuccess {Number}   towns.id           鄉鎮 ID
   * @apiSuccess {String}   towns.name         鄉鎮 名稱
   * @apiSuccess {Number}   towns.code         鄉鎮 郵遞區號
   * @apiSuccess {Number}   towns.cwb_id       鄉鎮 對應 中央氣象局 資料 ID
   * @apiSuccess {Array}    towns.position     鄉鎮 位置
   * @apiSuccess {Number}   towns.lat          鄉鎮 位置 緯度
   * @apiSuccess {Number}   towns.lng          鄉鎮 位置 經度
   * @apiSuccess {Number}   towns.zoom         鄉鎮在地圖上顯示級數，小於、等於地圖 zoom 則顯示
   *
   * @apiSuccessExample {json} Success Response:
   *     HTTP/1.1 200 OK
   *     {
   *         "id": "1",
   *         "name": "台北市",
   *         "towns": [
   *             {
   *                 "id": "1",
   *                 "name": "中正區",
   *                 "code": "100",
   *                 "cwb_id": "6300500",
   *                 "position": {
   *                     "lat": "25.0421407",
   *                     "lng": "121.5198716",
   *                     "zoom": "9"
   *                 }
   *             }
   *         ]
   *     }
   */

  /**
   * @api {get} https://works.ioa.tw/weather/api/towns/:id.json 03_取得指定鄉鎮資訊
   * @apiGroup Weather API
   *
   * @apiParam {Number}     id                 鄉鎮 ID
   *
   * @apiSuccess {Number}   id                 鄉鎮 ID
   * @apiSuccess {String}   name               鄉鎮 名稱
   * @apiSuccess {Number}   code               鄉鎮 郵遞區號
   * @apiSuccess {Array}    position           鄉鎮 位置
   * @apiSuccess {Number}   lat                鄉鎮 位置 緯度
   * @apiSuccess {Number}   lng                鄉鎮 位置 經度
   * @apiSuccess {Number}   zoom               鄉鎮在地圖上顯示級數，小於、等於地圖 zoom 則顯示
   * @apiSuccess {Array}    cate               鄉鎮 所屬的縣市
   * @apiSuccess {Number}   cate.id            縣市 ID
   * @apiSuccess {String}   cate.name          縣市 名稱
   * @apiSuccess {String}   img                鄉鎮 圖片
   *
   * @apiSuccessExample {json} Success Response:
   *     HTTP/1.1 200 OK
   *     {
   *         "id": "1",
   *         "name": "中正區",
   *         "code": "100",
   *         "position": {
   *             "lat": "25.0421407",
   *             "lng": "121.5198716",
   *             "zoom": "9"
   *         },
   *         "cate": {
   *             "id": "1",
   *             "name": "台北市"
   *         },
   *         "img": "https://works.ioa.tw/weather/img/towns/1/1/v.jpg"
   *     }
   */

  /**
   * @api {get} https://works.ioa.tw/weather/api/url.json 04_取得網址路徑
   * @apiGroup Weather API
   *
   * @apiSuccess {String}   img            天氣、特別預報 圖檔路徑
   *
   * @apiSuccessExample {json} Success Response:
   *     HTTP/1.1 200 OK
   *     {
   *         "img": "https://works.ioa.tw/weather/img/weathers/zeusdesign/"
   *     }
   */

  /**
   * @api {get} https://works.ioa.tw/weather/api/weathers/:id.json 05_取得指定鄉鎮天氣
   * @apiGroup Weather API
   *
   * @apiParam {Number}     id                     鄉鎮 ID
   *
   * @apiSuccess {String}   img                    天氣圖檔名稱
   * @apiSuccess {String}   desc                   敘述
   * @apiSuccess {Number}   temperature            溫度
   * @apiSuccess {Number}   humidity               濕度
   * @apiSuccess {Number}   rainfall               雨量
   * @apiSuccess {Number}   sunrise                日出時間
   * @apiSuccess {Number}   sunset                 日落時間
   * @apiSuccess {DateTime} at                     更新時間
   *
   * @apiSuccess {Array}    specials               特別預報
   * @apiSuccess {String}   specials.title         特別預報 標題
   * @apiSuccess {String}   specials.status        特別預報 狀態
   * @apiSuccess {String}   specials.desc          特別預報 敘述
   * @apiSuccess {DateTime} specials.at            特別預報 發佈時間
   * @apiSuccess {String}   specials.img           特別預報 天氣圖檔名稱
   *
   * @apiSuccess {Array}    histories              天氣歷史紀錄(包含當下最多 12 筆)
   * @apiSuccess {String}   histories.img          天氣歷史紀錄 天氣圖檔名稱
   * @apiSuccess {String}   histories.desc         天氣歷史紀錄 敘述
   * @apiSuccess {Number}   histories.temperature  天氣歷史紀錄 溫度
   * @apiSuccess {Number}   histories.humidity     天氣歷史紀錄 濕度
   * @apiSuccess {Number}   histories.rainfall     天氣歷史紀錄 雨量
   * @apiSuccess {Number}   histories.sunrise      天氣歷史紀錄 日出時間
   * @apiSuccess {Number}   histories.sunset       天氣歷史紀錄 日落時間
   * @apiSuccess {DateTime} histories.at           天氣歷史紀錄 更新時間
   *
   * @apiSuccessExample {json} Success Response:
   *     HTTP/1.1 200 OK
   *     {
   *         "img": "36@2x.png",
   *         "desc": "午後短暫雷陣雨",
   *         "temperature": "27",
   *         "humidity": "92",
   *         "rainfall": "5.0",
   *         "sunrise": "05:12",
   *         "sunset": "18:47",
   *         "at": "2016-07-11 14:28:27",
   *         "specials": [
   *             {
   *                 "title": "大雨特報",
   *                 "status": "大雨",
   *                 "desc": "西南風增強，易有短時強降雨，今（１１）日臺南市、高雄市及屏東縣有局部大雨或豪雨發生的機率，中部以北地區、宜蘭地區及花蓮山區有局部大雨發生的機率，請注意雷擊及強陣風；連日降雨，亦請注意坍方、落石，民眾應避免進入山區及河川活動。",
   *                 "at": "2016-07-11 12:05:00",
   *                 "img": "Heavy-rain.png"
   *             }
   *         ],
   *         "histories": [
   *             {
   *                 "img": "36@2x.png",
   *                 "desc": "午後短暫雷陣雨",
   *                 "temperature": "27",
   *                 "humidity": "92",
   *                 "rainfall": "5.0",
   *                 "sunrise": "05:12",
   *                 "sunset": "18:47",
   *                 "at": "2016-07-11 14:27:07"
   *             }
   *         ]
   *     }
   */
