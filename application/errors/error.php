<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo isset ($title) && $title ? $title : '錯誤!';?></title>
    <style type="text/css">
      @font-face {
        font-family: "Monaco";
        src: url(/resource/font/monaco/monaco.ttf) format('truetype'), url(/resource/font/monaco/monaco.otf) format('opentype');
      }
      * {
        vertical-align: top;
        -moz-osx-font-smoothing: antialiased;
        -webkit-font-smoothing: antialiased;
        -moz-font-smoothing: antialiased;
        -ms-font-smoothing: antialiased;
        -o-font-smoothing: antialiased;
      }
      *::-moz-selection {
        color: #edeff4;
        background-color: #96b4e6;
      }
      *::selection {
        color: #edeff4;
        background-color: #96b4e6;
      }
      *, *:after, *:before {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }

      html {
        height: 100%;
      }
      html body {
        position: relative;
        display: inline-block;
        text-align: center;
        margin: 0;
        width: 100%;
        height: 100%;
        font-size: medium;
        font-family: Monaco;
        background: #e9eaed url(data:image/png;base64,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);
        color: rgba(50, 50, 50, 0.7);
        -moz-osx-font-smoothing: antialiased;
        -webkit-font-smoothing: antialiased;
        -moz-font-smoothing: antialiased;
        -ms-font-smoothing: antialiased;
        -o-font-smoothing: antialiased;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
      }

      .container {
        width: 960px;
        margin: 0 auto;
        text-align: left;
      }
      .container .info {
        margin: 15px auto;
        border: 1px solid rgba(255, 0, 0, 0.3);
        padding: 3px 8px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        color: #31708f;
        background-color: #d9edf7;
        border: 1px solid #bce8f1;
      }
      .container .create {
        border: 0 solid transparent;
        color: rgba(255, 255, 255, 0.85);
        padding: 2px 10px 4px 10px;
        font-size: 15px;
        cursor: pointer;
        text-decoration: none;
        margin: 0;
        padding: 5px 15px;
        font-weight: bolder;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        -moz-box-shadow: inset -1px -1px 2px rgba(0, 0, 0, 0.1), inset 1px 1px 2px rgba(255, 255, 255, 0.1);
        -webkit-box-shadow: inset -1px -1px 2px rgba(0, 0, 0, 0.1), inset 1px 1px 2px rgba(255, 255, 255, 0.1);
        box-shadow: inset -1px -1px 2px rgba(0, 0, 0, 0.1), inset 1px 1px 2px rgba(255, 255, 255, 0.1);
        -moz-transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        -o-transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        -webkit-transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #2196f3;
      }
      .container .create[disabled] {
        color: rgba(255, 255, 255, 0.75);
        cursor: not-allowed;
        text-shadow: none;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
      }
      .container .create[disabled]:hover {
        color: rgba(255, 255, 255, 0.75);
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        text-shadow: none;
      }
      .container .create:hover {
        color: white;
        -moz-box-shadow: inset -1px -1px 2px rgba(0, 0, 0, 0.2), inset 1px 1px 2px rgba(255, 255, 255, 0.2);
        -webkit-box-shadow: inset -1px -1px 2px rgba(0, 0, 0, 0.2), inset 1px 1px 2px rgba(255, 255, 255, 0.2);
        box-shadow: inset -1px -1px 2px rgba(0, 0, 0, 0.2), inset 1px 1px 2px rgba(255, 255, 255, 0.2);
      }
      .container .create:active {
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        -moz-box-shadow: inset 2px 2px 10px rgba(0, 0, 0, 0.225);
        -webkit-box-shadow: inset 2px 2px 10px rgba(0, 0, 0, 0.225);
        box-shadow: inset 2px 2px 10px rgba(0, 0, 0, 0.225);
        -moz-transition: none;
        -o-transition: none;
        -webkit-transition: none;
        transition: none;
      }
      .container .create:focus {
        outline: 0;
      }
      .container .create:hover {
        background-color: #178ce9;
      }
      .container .create[disabled] {
        background-color: rgba(43, 160, 253, 0.85);
      }
      .container .table-list {
        margin: 10px 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
      }
      .container .table-list, .container .table-list:after, .container .table-list:before, .container .table-list *, .container .table-list *:after, .container .table-list *:before {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      .container .table-list thead tr {
        background-color: rgba(39, 40, 34, 0.15);
      }
      .container .table-list thead tr:first-child th:first-child {
        -moz-border-radius-topleft: 3px;
        -webkit-border-top-left-radius: 3px;
        border-top-left-radius: 3px;
      }
      .container .table-list thead tr:first-child th:last-child {
        -moz-border-radius-topright: 3px;
        -webkit-border-top-right-radius: 3px;
        border-top-right-radius: 3px;
      }
      .container .table-list thead tr:not(:first-child) th {
        border-top: 0;
      }
      .container .table-list thead tr th {
        border: 1px solid rgba(39, 40, 34, 0.25);
        border-right: 0;
        padding: 5px;
        text-align: left;
      }
      .container .table-list thead tr th:last-child {
        border-right: 1px solid rgba(39, 40, 34, 0.25);
      }
      .container .table-list tbody tr:last-child td:first-child {
        -moz-border-radius-bottomleft: 3px;
        -webkit-border-bottom-left-radius: 3px;
        border-bottom-left-radius: 3px;
      }
      .container .table-list tbody tr:last-child td:last-child {
        -moz-border-radius-bottomright: 3px;
        -webkit-border-bottom-right-radius: 3px;
        border-bottom-right-radius: 3px;
      }
      .container .table-list tbody tr:hover {
        background-color: rgba(39, 40, 34, 0.03);
      }
      .container .table-list tbody tr td {
        border: 1px solid rgba(39, 40, 34, 0.25);
        border-right: 0;
        border-top: 0;
        background-color: rgba(255, 255, 255, 0.5);
        padding: 5px 8px;
        text-align: left;
        word-break:break-all;
      }
      .container .table-list tbody tr td:last-child {
        border-right: 1px solid rgba(39, 40, 34, 0.25);
      }
      .container .table-list tbody tr td svg {
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
      }
      .container .table-list tbody tr td svg, .container .table-list tbody tr td svg * {
        color: rgba(39, 40, 34, 0.6);
        fill: rgba(39, 40, 34, 0.6);
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
      }
      .container .table-list tbody tr td svg:hover, .container .table-list tbody tr td svg:hover * {
        color: #272822;
        fill: #272822;
      }
      .container .table-list tbody tr td input[type='text'] {
        width: 250px;
        padding: 5px;
        border: 1px solid rgba(39, 40, 34, 0.25);
        font-size: 16px;
        color: rgba(50, 50, 50, 0.7);
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
        width: 100%;
      }
      .container .table-list tbody tr td input[type='text']:focus {
        outline: 0;
        border: 1px solid #66afe9;
        -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
      }
      .container .table-list tbody tr td input[type='text'][type="file"] {
        font-size: 14px;
      }
      .container .table-list tbody tr td a {
        display: inline-block;
        color: rgba(39, 40, 34, 0.6);
        font-weight: bold;
        text-decoration: none;
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
      }
      .container .table-list tbody tr td a:hover {
        color: #272822;
      }
    </style>
  </head>
  <body>
    <div class='container'>

      <table class='table-list'>
        <thead>
          <tr>
            <th><?php echo isset ($title) && $title ? $title : '錯誤訊息';?></th>
          </tr>
        </thead>
        <tbody>
    <?php if ($args) {
            foreach ($args as $arg) { ?>
              <tr>
                <td><?php echo $arg;?></td>
              </tr>
      <?php }
          } else { ?>
            <tr>
              <td>不明原因錯誤!</td>
            </tr>
    <?php }?>
        </tbody>
      </table>
<?php if ($trace) { ?>
        <table class='table-list'>
          <thead>
            <tr>
              <th>Trace list</th>
              <th width='80' style='text-align: center;'>Line</th>
            </tr>
          </thead>
          <tbody>
      <?php foreach ($trace as $t) { ?>
              <tr>
                <td><?php echo $t['file'];?></td>
                <td style='text-align: center;'><?php echo $t['line'];?></td>
              </tr>
      <?php } ?>
          </tbody>
        </table>
<?php }?>
    </div>
  </body>
</html>