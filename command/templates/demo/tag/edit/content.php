<div class='container'>
{<{<{ echo render_cell ('demo_cell', 'main_menu', array ()); ?>
  <a class='list' href='{<{<{ echo base_url (array ('tags', 'index'));?>'>列表</a>
  {<{<{
    if (isset ($message) && $message) { ?>
      <div class='error'>{<{<{ echo $message;?></div>
  {<{<{
    } ?>
  <form action='{<{<{ echo base_url (array ('tags', 'update', $tag->id));?>' method='post' enctype='multipart/form-data'>
    <table class='table-form'>
      <tbody>
        <tr>
          <th>名稱</th>
          <td>
            <input type='text' name='name' value='{<{<{ echo $tag->name;?>' placeholder='請輸入標籤名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
          </td>
        </tr>
        <tr>
          <td colspan='2'>
            <button type='reset' class='button'>重填</button>
            <button type='submit' class='button'>確定</button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
