<!-- enctype="multipart/form-data"  -->

<div class='container'>
  <form action="<?php echo base_url (array ('demo_post', 'post1_submit'));?>" method="post">
    <h4>一般</h4>
    <input type='text' name='account' value='oa' placeholder='account'/>
    <hr/>
    <button type='submit'>送出</button>
  </form>

  <form action="<?php echo base_url (array ('demo_post', 'post2_submit'));?>" method="post">
    <h4>陣列</h4>
    <input type='text' name='items[]' value='a' placeholder='items[]'/>
    <input type='text' name='items[]' value='b' placeholder='items[]'/>
    <input type='text' name='items[]' value='c' placeholder='items[]'/>
    <input type='text' name='items[]' value='d' placeholder='items[]'/>
    <hr/>
    <button type='submit'>送出</button>
  </form>

  <form action="<?php echo base_url (array ('demo_post', 'post3_submit'));?>" method="post" enctype="multipart/form-data">
    <h4>圖片</h4>
    <input type='file' name='pic' value=''/>
    <hr/>
    <button type='submit'>送出</button>
  </form>

  <form action="<?php echo base_url (array ('demo_post', 'post4_submit'));?>" method="post" enctype="multipart/form-data">
    <h4>圖片陣列</h4>
    <input type='file' name='pics[]' value=''/>
    <input type='file' name='pics[]' value=''/>
    <input type='file' name='pics[]' value=''/>
    <input type='file' name='pics[]' value=''/>
    <hr/>
    <button type='submit'>送出</button>
  </form>

  <form action="<?php echo base_url (array ('demo_post', 'post5_submit'));?>" method="post" enctype="multipart/form-data">
    <h4>圖片與ORM</h4>
    <input type='file' name='pic' value=''/>
    <hr/>
    <button type='submit'>送出</button>
  </form>
</div>

